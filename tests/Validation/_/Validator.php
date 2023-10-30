<?php

namespace Tests\Validation\_;

class Validator
{

    private array $items = [];
    private array $values = [];

    private string $mode = '';
    private string $method = '';

    private array $data;

    const MODE_API = 'API';
    const MODE_WEB = 'WEB';

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    private const FUNC_PRIORITY = [
        'nullable',
        'required',
        'integer',
        'string',
        'arr',
        'ncode',
        'min',
        'max',
        'between',
        'in'
    ];

    function __construct($mode = 'API', $method = 'GET')
    {
        $this->mode = in_array(strtoupper(trim($mode)), ['WEB', 'API']) ? strtoupper(trim($mode)) : 'API';
        $this->method = in_array(strtoupper(trim($method)), ['GET', 'POST', 'PUT', 'DELETE']) ? strtoupper(trim($method)) : 'GET';
    }

    protected function add($name, $rule)
    {

    }

    protected function _set_data_($data)
    {
        $this->data = $data;
    }

    private function nullable($field): bool
    {
        return FALSE;
    }

    private function required($field): bool
    {
        return FALSE;
    }

    private function integer($field): bool
    {
        return FALSE;
    }

    private function string($field): bool
    {
        return FALSE;
    }

    private function arr($filed): bool
    {
        return FALSE;
    }

    private function min($field, $min): bool
    {
        return FALSE;
    }

    private function max($field, $to): bool
    {
        return FALSE;
    }

    private function between($field, $from, $to): bool
    {
        return FALSE;
    }

    private function ncode(): bool
    {
        return FALSE;
    }

    private function slug(): bool
    {
        return FALSE;
    }

    private function unique(): bool
    {
        return FALSE;
    }

    private function in($field, $in): bool
    {
        return FALSE;
    }

    protected function check(array $rules): bool
    {
        foreach ($this->data as $name => $value) {
            $name = strip_tags(trim($name));
            // iterate FUNC_PRIORITY
            foreach (self::FUNC_PRIORITY as $func_name) {
                $rules_str = isset($rules[$name]) && $rules[$name] ? $rules[$name] : '';
                $rules = explode('|', $rules_str);
                $rules = array_map(function($callable_name) {
                    $callable_name = strtolower(strip_tags(trim($callable_name)));
                    if (!in_array($callable_name, self::FUNC_PRIORITY)) {
                        throw new \Exception('InvalidCallable');
                    }
                    return $callable_name;
                }, $rules);

                $this->{$func_name}();
            }
        }

        return TRUE;
    }


    protected function get_inputs(): array
    {
        if ($this->data) {
            return $this->data;
        }

        $this->data = $this->get_inputs_data();

        return $this->data;
    }


    private function get_inputs_data(): array
    {
        $data = [];
        switch ($this->mode) {
            case self::MODE_API:
                $data = $this->get_inputs_api_data();
                break;
            case self::MODE_WEB:
                $data = $this->get_inputs_web_data();
                break;
        }

        return $data;
    }


    private function get_inputs_api_data(): array
    {
        $data = [];
        $input = file_get_contents('php://input');
        try {
            $data = json_decode($input);
        } catch (\Exception $ex) {
        }
        return $data;
    }


    private function get_inputs_web_data(): array
    {
        $data = [];

        switch ($this->method) {
            case self::METHOD_GET:
                $data = isset($_GET) && $_GET ? $_GET : [];
                break;
            case self::METHOD_POST:
            case self::METHOD_PUT:
            case self::METHOD_DELETE:
                $data = isset($_POST) && $_POST ? $_POST : [];
                break;
        }

        return $data;
    }

}