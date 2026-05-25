<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WebApplicationFirewall
{
    protected array $patterns = [
        'sqli' => [
            '/(\bSELECT\b.*\bFROM\b)/i',
            '/(\bUNION\b.*\bSELECT\b)/i',
            '/(\bINSERT\b.*\bINTO\b)/i',
            '/(\bDELETE\b.*\bFROM\b)/i',
            '/(\bUPDATE\b.*\bSET\b)/i',
            '/(\bDROP\b.*\bTABLE\b)/i',
            '/(\bALTER\b.*\bTABLE\b)/i',
            '/(\bTRUNCATE\b.*\bTABLE\b)/i',
            '/(\bCREATE\b.*\bTABLE\b)/i',
            '/(\/\*.*\*\/)/',
            '/\bEXEC\s*\(/i',
            '/\bxp_cmdshell\b/i',
            '/\bpg_sleep\b/i',
            '/\bWAITFOR\b.*\bDELAY\b/i',
            '/\bBENCHMARK\b.*\bRAND\b/i',
            '/\bSLEEP\s*\(/i',
            '/\bINFORMATION_SCHEMA\b/i',
            '/\bHAVING\b/i',
        ],
        'xss' => [
            '/<script[^>]*>/i',
            '/<\/script>/i',
            '/javascript\s*:/i',
            '/\bon\w+\s*=/i',             // onerror=, onload=, onclick=, etc.
            '/alert\s*\(/i',
            '/prompt\s*\(/i',
            '/confirm\s*\(/i',
            '/document\.cookie/i',
            '/window\.location/i',
            '/eval\s*\(/i',
            '/<[^>]*\s*on\w+\s*=/i',      // <img onerror= etc
        ],
        'cmdi' => [
            '/;\s*(ls|cat|id|whoami|pwd|rm|wget|curl|nc|bash|sh|python|perl|php)\b/i',
            '/`[^`]+`/',
            '/\$(?:\(|\{)/',
            '/\b(?:exec|system|passthru|shell_exec|popen|proc_open|pcntl_exec)\s*\(/i',
            '/\|\s*(?:ls|cat|id|whoami|pwd|rm|wget|curl|nc|bash|sh|python|perl|php)\b/i',
            '/\bwget\s+/i',
            '/\bcurl\s+/i',
        ],
        'path_traversal' => [
            '/\.\.\/\.\.\//',
            '/(?:etc\/passwd|etc\/shadow|etc\/hosts)/i',
            '/%00/',
        ],
        'file_inclusion' => [
            '/file_get_contents\s*\(/i',
            '/base64_decode\s*\(/i',
            '/include\s*\(/i',
            '/require\s*\(/i',
        ],
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->isAttack($request)) {
            $this->logAttack($request);
            return response('Forbidden', 403);
        }

        return $next($request);
    }

    protected function isAttack(Request $request): bool
    {
        $inputs = $request->all();
        array_walk_recursive($inputs, function (&$value) {
            if (is_string($value)) {
                $value = urldecode($value);
            }
        });

        foreach ($inputs as $key => $value) {
            if ($this->matchesAny($value)) {
                return true;
            }
        }

        foreach ($request->header() as $key => $values) {
            if (in_array($key, ['cookie', 'sec-fetch-site', 'sec-fetch-mode', 'sec-fetch-dest', 'sec-ch-ua'], true)) {
                continue;
            }
            foreach ($values as $value) {
                if (is_string($value) && $this->matchesAny($value)) {
                    return true;
                }
            }
        }

        $uri = urldecode($request->path());
        if ($this->matchesAny($uri)) {
            return true;
        }

        if (($request->isMethod('post') || $request->isMethod('put') || $request->isMethod('patch'))
            && !str_contains($request->header('Content-Type', ''), 'application/json')) {
            $raw = $request->getContent();
            if (!empty($raw) && $this->matchesAny($raw)) {
                return true;
            }
        }

        return false;
    }

    protected function matchesAny(mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        foreach ($this->patterns as $category => $patterns) {
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $value)) {
                    return true;
                }
            }
        }
        return false;
    }

    protected function logAttack(Request $request): void
    {
        logger()->warning('WAF blocked request', [
            'ip' => $request->ip(),
            'method' => $request->method(),
            'uri' => $request->fullUrl(),
            'user_agent' => $request->userAgent(),
            'input_keys' => array_keys($request->all()),
        ]);
    }
}
