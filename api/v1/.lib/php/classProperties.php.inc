<?php

// 汎用のため明示的にグローバル名前空間（namespaceなし）に設置
namespace

{
    class Properties
    {
        use PropertyValuesAllowed;
        use PropertyKeysForApi;

        /* ------------------------------------------------------ [Construct] */

        public function __construct(array $settings)
        {
            $this->setProperties($settings);
        }

        /* -------------------------------------------------------------- [S] */

        private function setProperties(array $settings)
        {
            $this->access_token = $this->fetchAccessToken($settings);
            $this->endpoint     = $this->fetchEndpoint($settings);
            $this->host         = $this->fetchHost($settings);
            $this->method       = $this->fetchMethod($settings);
            $this->schema       = $this->fetchSchema($settings);
            $this->spoiler_text = $this->fetchSpoilerText($settings);
            $this->status       = $this->fetchStatus($settings);
            $this->visibility   = $this->fetchVisibility($settings);
        }

        /* -------------------------------------------------------------- [F] */

        private function fetchAccessToken(array $settings)
        {
            $string = getValue('access_token', $settings, '');

            $this->name_service = getValue('name_service', $settings, '');
            $this->name_token   = getValue('name_token', $settings, '');

            if (! empty($string)) {
                if (! isValidFormatToken($string)) {
                    dieMsg('Invalid access token format.', __LINE__);
                }

                return $string;
            }

            if (empty($this->name_service) || empty($this->name_token)) {
                $msg = 'No access token specified or bad `gettoken` request.';
                dieMsg($msg, __LINE__);
            }

            return getAccessToken($this->name_service, $this->name_token);
        }

        private function fetchEndpoint(array $settings)
        {
            $string    = getValue('endpoint', $settings);
            $endpoints = $this->allow['endpoint'];

            return in_array($string, $endpoints) ? $string : '';
        }

        private function fetchHost(array $settings)
        {
            $string = getValue('host', $settings);
            $hosts  = $this->allow['host'];

            return in_array($string, $hosts) ? $string : $this->host;
        }

        private function fetchMethod(array $settings)
        {
            $string  = strtoupper(getValue('method', $settings));
            $methods = $this->allow['method'];

            return in_array($string, $methods) ? $string : $this->method;
        }

        private function fetchSchema(array $settings)
        {
            $string  = strtolower(getValue('schema', $settings));
            $schemas = $this->allow['schema'];

            return in_array($string, $schemas) ? $string : $this->schema;
        }

        private function fetchSpoilerText(array $settings)
        {
            $string = getValue('spoiler_text', $settings, '');

            return trim($string);
        }

        private function fetchStatus(array $settings)
        {
            $string = getValue('status', $settings, '');

            return trim($string);
        }

        private function fetchVisibility(array $settings)
        {
            $string       = strtolower(getValue('visibility', $settings));
            $visibilities = $this->allow['visibility'];

            return in_array($string, $visibilities) ? $string : $this->visibility;
        }
    }
}
