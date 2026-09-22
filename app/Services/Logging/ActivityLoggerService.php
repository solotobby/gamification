<?php

namespace App\Services\Logging;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Throwable;

class ActivityLoggerService
{
    /**
     * Record an activity log with complete device, client, and user context.
     *
     * @param User|int|null $user
     * @param string $activityType
     * @param string $description
     * @param string $userType
     * @param array $properties
     * @param Request|null $request
     * @param string|null $action
     * @return ActivityLog|null
     */
    public static function log(
        User|int|null $user,
        string $activityType,
        string $description,
        string $userType = 'regular',
        array $properties = [],
        ?Request $request = null,
        ?string $action = null
    ): ?ActivityLog {
        try {
            $request = $request ?: request();
            $userId = $user instanceof User ? $user->id : (is_numeric($user) ? (int) $user : null);

            // Infer user_type if not provided
            if ($user instanceof User && ($userType === 'regular' || empty($userType))) {
                $role = strtolower($user->role ?? '');
                if ($role === 'admin' || $role === 'super_admin' || $role === 'staff') {
                    $userType = 'admin';
                }
            }

            $deviceContext = self::resolveDeviceContext($request);

            $actionName = $action;
            if (empty($actionName) && $request) {
                $actionName = $request->method() . ' ' . $request->path();
            }

            return ActivityLog::create([
                'user_id' => $userId,
                'activity_type' => $activityType,
                'description' => $description,
                'user_type' => $userType ?: 'regular',
                'client_type' => $deviceContext['client_type'],
                'device' => $deviceContext['device'],
                'platform' => $deviceContext['platform'],
                'browser' => $deviceContext['browser'],
                'device_model' => $deviceContext['device_model'],
                'ip_address' => $deviceContext['ip_address'],
                'user_agent' => $deviceContext['user_agent'],
                'action' => $actionName,
                'properties' => !empty($properties) ? $properties : null,
            ]);
        } catch (Throwable $e) {
            logger()->error('Failed to write activity log: ' . $e->getMessage(), [
                'activity_type' => $activityType,
                'description' => $description,
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    /**
     * Log regular user activity.
     */
    public static function logUser(
        User|int|null $user,
        string $activityType,
        string $description,
        array $properties = [],
        ?Request $request = null,
        ?string $action = null
    ): ?ActivityLog {
        return self::log($user, $activityType, $description, 'regular', $properties, $request, $action);
    }

    /**
     * Log administrator activity.
     */
    public static function logAdmin(
        User|int|null $adminUser,
        string $activityType,
        string $description,
        array $properties = [],
        ?Request $request = null,
        ?string $action = null
    ): ?ActivityLog {
        return self::log($adminUser, $activityType, $description, 'admin', $properties, $request, $action);
    }

    /**
     * Extract detailed device, client type (web vs app), OS, and IP context from Request.
     */
    public static function resolveDeviceContext(?Request $request = null): array
    {
        if (!$request) {
            return [
                'client_type' => 'system',
                'device' => 'server',
                'platform' => PHP_OS,
                'browser' => 'CLI',
                'device_model' => 'Console',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Console/Artisan',
            ];
        }

        $device = ContextExtractor::extractDevice($request);
        $userAgent = $device['user_agent'] ?? ($request->userAgent() ?: '');
        $ip = $device['ip'] ?? ContextExtractor::extractClientIp($request);

        // 1. Detect Client Type (web, app, api)
        $clientType = 'web';

        $headerClientType = $request->header('X-Client-Type')
            ?: $request->header('Client-Type');

        $deviceSource = $request->header('X-Device-Source')
            ?: $request->header('Device-Source');

        $platformHeader = $request->header('X-Platform')
            ?: $request->header('Platform');

        if ($headerClientType) {
            $normalized = strtolower(trim($headerClientType));
            if (in_array($normalized, ['app', 'mobile_app', 'ios', 'android', 'flutter', 'react_native'])) {
                $clientType = 'app';
            } elseif ($normalized === 'web') {
                $clientType = 'web';
            } elseif ($normalized === 'api') {
                $clientType = 'api';
            } else {
                $clientType = $normalized;
            }
        } elseif ($deviceSource === 'app' || in_array(strtolower($platformHeader ?? ''), ['android', 'ios', 'app', 'mobile'])) {
            $clientType = 'app';
        } elseif (strtolower($platformHeader ?? '') === 'web') {
            $clientType = 'web';
        } else {
            if (stripos($userAgent, 'Dart') !== false || stripos($userAgent, 'Flutter') !== false || stripos($userAgent, 'FreebyzApp') !== false || stripos($userAgent, 'CFNetwork') !== false) {
                $clientType = 'app';
            } elseif (stripos($userAgent, 'PostmanRuntime') !== false || stripos($userAgent, 'curl') !== false || stripos($userAgent, 'GuzzleHttp') !== false) {
                $clientType = 'api';
            } else {
                $clientType = 'web';
            }
        }

        // 2. Resolve Device Type (mobile, desktop, tablet)
        $deviceType = strtolower($device['device_type'] ?? 'desktop');
        if (!in_array($deviceType, ['mobile', 'tablet', 'desktop'])) {
            $deviceType = 'desktop';
        }
        if ($clientType === 'app' && $deviceType === 'desktop') {
            $deviceType = 'mobile';
        }

        // 3. Resolve Platform / OS
        $platform = $device['platform'] ?? ($device['os'] ?? 'Unknown OS');
        if ($platformHeader && strtolower($platformHeader) !== 'web') {
            $platform = $platformHeader;
        }

        // 4. Resolve Browser & Model
        $browser = $device['browser'] ?? 'Unknown Browser';
        $deviceModel = $device['device_model'] ?: ($device['device_name'] ?: ($device['summary'] ?: $platform));

        return [
            'client_type' => $clientType,
            'device' => $deviceType,
            'platform' => $platform,
            'browser' => $browser,
            'device_model' => substr($deviceModel, 0, 150),
            'ip_address' => $ip,
            'user_agent' => $userAgent,
        ];
    }
}
