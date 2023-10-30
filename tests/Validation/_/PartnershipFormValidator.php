<?php

namespace Tests\Validation\_;


class PartnershipFormValidator extends Validator
{

    function __construct($mode = 'API', $method = 'GET')
    {
        parent::__construct($mode, $method);
    }

    public function make(array $rules): bool
    {
        return parent::check($rules);
    }

    public function data()
    {
        parent::get_inputs();
    }

    // temporary
    public function _set_data($data)
    {
        $this->_set_data_($data);
    }

    public function get_error(): array
    {

    }

}