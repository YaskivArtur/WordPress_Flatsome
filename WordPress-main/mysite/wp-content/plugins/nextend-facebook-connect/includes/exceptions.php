<?php

class NSLContinuePageRenderException extends Exception {

}

class NSLSanitizedRequestErrorMessageException extends Exception {

    public function __construct($message = "", $code = 0, Throwable $previous = null) {
        $message = sanitize_text_field($message);
        parent::__construct($message, $code, $previous);
    }
}