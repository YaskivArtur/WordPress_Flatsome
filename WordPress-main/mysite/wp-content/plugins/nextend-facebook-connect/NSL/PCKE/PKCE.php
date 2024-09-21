<?php

namespace NSL\PKCE;

use Exception;


/**
 * This class provides a simple interface for PKCE code_verifier and code_challenge generation.
 *
 *
 * @version     v8.5.0 (2023-03-28)
 * @link        https://auth0.com/docs/libraries/auth0-php          Project URL
 * @link        https://github.com/auth0/auth0-PHP         GitHub Repo
 * @author      auth0 <https://auth0.com>
 * @license     http://opensource.org/licenses/mit-license.php  MIT License
 *
 *
 * Code adjusted by Nextendweb for Nextend Social Login:
 *              - fix: throw simple exception
 *              - fix: code structure
 *
 */
final class PKCE {

    /**
     * Returns the generated code challenge from the given code_verifier. The
     * code_challenge should be a Base64 encoded string with URL and
     * filename-safe characters. The trailing '=' characters should be removed
     * and no line breaks, whitespace, or other additional characters should be
     * present.
     *
     * @param string $codeVerifier string to generate code challenge from
     *
     * @return string
     * @see https://auth0.com/docs/flows/concepts/auth-code-pkce
     *
     */
    public static function generateCodeChallenge($codeVerifier): string {
        $encoded = base64_encode(hash('sha256', $codeVerifier, true));

        return strtr(rtrim($encoded, '='), '+/', '-_');
    }

    /**
     * Generate a random string of between 43 and 128 characters containing
     * letters, numbers and "-", ".", "_", "~", as defined in the RFC 7636
     * specification.
     *
     * @param int $length Code verifier length
     *
     * @return string
     * @throws Exception
     *
     * @see https://tools.ietf.org/html/rfc7636
     */
    public static function generateCodeVerifier($length = 43): string {
        if ($length < 43 || $length > 128) {
            throw  new Exception('Code verifier must be created with a minimum length of 43 characters and a maximum length of 128 characters!');
        }

        $string = '';

        while (($len = mb_strlen($string)) < $length) {
            $size = $length - $len;
            $size = $size >= 1 ? $size : 1;

            // @codeCoverageIgnoreStart
            try {
                $bytes = random_bytes($size);
            } catch (Exception $e) {
                $bytes = openssl_random_pseudo_bytes($size);
            }
            // @codeCoverageIgnoreEnd

            $string .= mb_substr(str_replace([
                '/',
                '+',
                '='
            ], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }
}
