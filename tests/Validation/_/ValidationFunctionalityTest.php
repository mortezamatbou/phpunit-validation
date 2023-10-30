<?php
declare(strict_types=1);


namespace Tests\Validation\_;

class ValidationFunctionalityTest extends \PHPUnit\Framework\TestCase
{

    private array $data = [];

    public function setUp(): void
    {
        $this->data = [
            [
                'data' => [
                    'name' => 'Morteza',
                    'family' => 'Matbou',
                    'age' => 31,
                    'birthDate' => '1371-11-25',
                    'arr' => [1, 'test'],
                    'info' => ['nationalCode' => '2640101234', 'bankingAccountNumber' => '700425123'],
                    'description' => 'Description of Morteza'
                ],
                'rules' => [
                    'name' => 'required|string',
                    'family' => 'required|string',
                    'age' => 'required|integer|min:1|max:100|reg:[a-z]*',
                    'birthDate' => 'required',
                    'arr' => 'required|array',
                    'info.nationalCode' => 'required|ncode',
                    'info.bankingAccount' => 'required|string',
                    'description' => 'nullable'
                ],
                'method' => Validator::METHOD_POST,
                'mode' => Validator::MODE_API
            ],
            [
                'data' => [
                    'name' => 'Hossein',
                    'family' => 'Allahmoradi',
                    'age' => 25,
                    'birthDate' => '1378-03-05',
                    'arr' => [1, 'test'],
                    'info' => ['nationalCode' => '2640104144', 'bankingAccountNumber' => '700425123'],
                    'description' => 'Description of Morteza'
                ],
                'rules' => [
                    'name' => 'required|string',
                    'family' => 'required|string',
                    'age' => 'required|integer|min:1|max:100|reg:(^[0-9]?[1-9]{1}$)',
                    'birthDate' => 'required',
                    'arr' => 'required|array',
                    'info.nationalCode' => 'required|ncode',
                    'info.bankingAccount' => 'required|string',
                    'description' => 'nullable'
                ],
                'method' => Validator::METHOD_POST,
                'mode' => Validator::MODE_API
            ]
        ];
    }

    public function testMain(): void
    {


        foreach ($this->data as $row) {
            $validator = new PartnershipFormValidator($row['mode'], $row['method']);
            $validator->_set_data($row['data']);
            $data = $validator->make($row['rules']);
            print_r($data);
        }
    }


}
