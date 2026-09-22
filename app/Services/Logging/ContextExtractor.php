<?php

namespace App\Services\Logging;

use Illuminate\Http\Request;
use Throwable;

class ContextExtractor
{
    /**
     * Extract client device, OS, browser, IP, and headers.
     *
     * @param Request|null $request
     * @return array
     */
    public static function extractDevice(?Request $request = null): array
    {
        if (!$request) {
            return [
                'summary' => 'CLI / Console Execution',
                'platform' => 'CLI',
                'os' => PHP_OS,
                'browser' => 'Artisan / Cron',
                'device_type' => 'desktop',
                'device_name' => 'Server',
                'device_model' => 'Console',
                'ip' => '127.0.0.1',
                'user_agent' => 'Console/Artisan',
            ];
        }

        $userAgent = $request->header('X-Client-User-Agent')
            ?: $request->header('X-User-Agent')
            ?: $request->header('X-Original-User-Agent')
            ?: ($request->userAgent() ?: 'Unknown User-Agent');
        $ip = self::extractClientIp($request);
        $parsed = self::parseUserAgent($userAgent);

        $customPlatform = $request->header('X-Platform') ?: $request->header('Platform');
        $customDeviceId = $request->header('X-Device-Id') ?: $request->header('Device-Id');
        $customAppVersion = $request->header('X-App-Version') ?: $request->header('App-Version');
        $customDeviceModel = $request->header('X-Device-Model') ?: $request->header('Device-Model');
        $customDeviceOS = $request->header('X-Device-OS') ?: $request->header('Device-OS');
        $customDeviceName = $request->header('X-Device-Name') ?: $request->header('Device-Name');

        $platform = $customPlatform ?: $parsed['os'];
        $osVersion = $customDeviceOS ?: $parsed['os_version'];
        $client = $customAppVersion ? "App v{$customAppVersion}" : $parsed['browser'];
        $model = $customDeviceModel ?: $parsed['device_type'];

        $summaryParts = [];
        if ($customDeviceName) {
            $summaryParts[] = $customDeviceName;
        } elseif ($customDeviceModel) {
            $summaryParts[] = $customDeviceModel;
        } else {
            $summaryParts[] = $platform . ($osVersion ? " {$osVersion}" : '');
        }

        if ($client) {
            $summaryParts[] = "({$client})";
        }

        $summary = implode(' ', array_filter($summaryParts)) ?: $userAgent;

        return [
            'summary' => $summary,
            'platform' => $platform,
            'os' => $parsed['os'],
            'os_version' => $osVersion,
            'browser' => $parsed['browser'],
            'device_type' => $parsed['device_type'],
            'device_id' => $customDeviceId,
            'device_model' => $customDeviceModel,
            'device_name' => $customDeviceName,
            'app_version' => $customAppVersion,
            'ip' => $ip,
            'user_agent' => $userAgent,
        ];
    }

    /**
     * Extract client IP with proxy and Cloudflare support.
     *
     * @param Request $request
     * @return string
     */
    public static function extractClientIp(Request $request): string
    {
        if ($clientIp = $request->header('X-Client-IP')) {
            $ip = trim(explode(',', $clientIp)[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }

        if ($cfIp = $request->header('CF-Connecting-IP')) {
            $ip = trim(explode(',', $cfIp)[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }

        if ($trueIp = $request->header('True-Client-IP')) {
            $ip = trim(explode(',', $trueIp)[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }

        if ($realIp = $request->header('X-Real-IP')) {
            $ip = trim(explode(',', $realIp)[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }

        if ($forwarded = $request->header('X-Forwarded-For')) {
            $ip = trim(explode(',', $forwarded)[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }

        return $request->ip() ?: 'Unknown IP';
    }

    /**
     * Parse User-Agent string to extract OS, Browser, and Device Type.
     *
     * @param string $userAgent
     * @return array
     */
    public static function parseUserAgent(string $userAgent): array
    {
        $os = 'Unknown OS';
        $osVersion = '';
        $browser = 'Unknown Browser';
        $deviceType = 'desktop';

        if (stripos($userAgent, 'PostmanRuntime') !== false) {
            return ['os' => 'Postman Client', 'os_version' => '', 'browser' => 'Postman', 'device_type' => 'desktop'];
        }
        if (stripos($userAgent, 'Dart') !== false || stripos($userAgent, 'Flutter') !== false) {
            return ['os' => 'Mobile App', 'os_version' => '', 'browser' => 'Flutter/Dart', 'device_type' => 'mobile'];
        }
        if (stripos($userAgent, 'axios') !== false || stripos($userAgent, 'GuzzleHttp') !== false || stripos($userAgent, 'curl') !== false) {
            return ['os' => 'HTTP Client', 'os_version' => '', 'browser' => 'HTTP Client', 'device_type' => 'desktop'];
        }

        if (preg_match('/iPhone|iPad|iPod/i', $userAgent)) {
            $os = 'iOS';
            $deviceType = stripos($userAgent, 'iPad') !== false ? 'tablet' : 'mobile';
            if (preg_match('/OS ([\d_]+)/i', $userAgent, $matches)) {
                $osVersion = str_replace('_', '.', $matches[1]);
            }
        } elseif (preg_match('/Android/i', $userAgent)) {
            $os = 'Android';
            $deviceType = stripos($userAgent, 'Mobile') !== false ? 'mobile' : 'tablet';
            if (preg_match('/Android ([\d.]+)/i', $userAgent, $matches)) {
                $osVersion = $matches[1];
            }
        } elseif (preg_match('/Windows NT ([\d.]+)/i', $userAgent, $matches)) {
            $os = 'Windows';
            $ntMap = [
                '10.0' => '10/11',
                '6.3' => '8.1',
                '6.2' => '8',
                '6.1' => '7',
            ];
            $osVersion = $ntMap[$matches[1]] ?? $matches[1];
            $deviceType = 'desktop';
        } elseif (preg_match('/Macintosh|Mac OS X ([\d_]+)/i', $userAgent, $matches)) {
            $os = 'macOS';
            $osVersion = isset($matches[1]) ? str_replace('_', '.', $matches[1]) : '';
            $deviceType = 'desktop';
        } elseif (preg_match('/Linux/i', $userAgent)) {
            $os = 'Linux';
            $deviceType = 'desktop';
        }

        if (preg_match('/Edg(?:e)?\/([\d.]+)/i', $userAgent, $matches)) {
            $browser = 'Microsoft Edge ' . explode('.', $matches[1])[0];
        } elseif (preg_match('/Chrome\/([\d.]+)/i', $userAgent, $matches) && stripos($userAgent, 'Chromium') === false) {
            $browser = 'Chrome ' . explode('.', $matches[1])[0];
        } elseif (preg_match('/Firefox\/([\d.]+)/i', $userAgent, $matches)) {
            $browser = 'Firefox ' . explode('.', $matches[1])[0];
        } elseif (preg_match('/Version\/([\d.]+).*Safari/i', $userAgent, $matches)) {
            $browser = 'Safari ' . explode('.', $matches[1])[0];
        } elseif (preg_match('/Opera|OPR\/([\d.]+)/i', $userAgent, $matches)) {
            $browser = 'Opera ' . (isset($matches[1]) ? explode('.', $matches[1])[0] : '');
        }

        return [
            'os' => $os,
            'os_version' => $osVersion,
            'browser' => $browser,
            'device_type' => $deviceType,
        ];
    }
}
