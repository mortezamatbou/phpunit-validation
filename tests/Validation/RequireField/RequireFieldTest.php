<?php
declare(strict_types=1);


namespace Tests\Validation\RequireField;


class RequireFieldTest extends \PHPUnit\Framework\TestCase
{

    private array $body = [
        'name' => 'Morteza',
        'family' => 'Matbou',
        'age' => 31,
        'field' => 'IT',
        'city' => 'Bandar-e Anzali'
    ];


    public function get_data(): array
    {
        return [
            ['0' => 'name', 'expected' => TRUE],
            ['1' => 'family', 'expected' => TRUE],
            ['2' => 'age', 'expected' => TRUE],
            ['3' => 'field', 'expected' => TRUE],
            ['4' => 'city', 'expected' => TRUE],
            ['5' => 'city ', 'expected' => TRUE],
            ['6' => 'City', 'expected' => FALSE],
            ['7' => 'status', 'expected' => FALSE],
        ];
    }

    /**
     * @dataProvider get_data
     * @param $item
     * @param bool $expected
     */
    public function testNotNull($item, bool $expected): void
    {
        $item = trim($item);
        $this->assertSame($expected, isset($this->body[$item]));
    }


}
