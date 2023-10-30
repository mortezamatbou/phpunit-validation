<?php
declare(strict_types=1);


namespace Tests\Validation\CallableFunctions;


class CallableFunctionsTest extends \PHPUnit\Framework\TestCase
{

    public function get_data_success(): array
    {
        return [
            ['0' => 'required', 'value' => 1, 'expected' => TRUE, 'format' => 'withoutParam'],
            ['1' => 'nullable', 'value' => '', 'expected' => TRUE, 'format' => 'withoutParam'],
            ['2' => 'in:1,2,3', 'value' => 1, 'expected' => TRUE, 'format' => 'withParam'],
            ['3' => 'array', 'value' => [1, "Test", 2], 'expected' => TRUE, 'format' => 'withoutParam'],
            ['4' => 'ncode', 'value' => 2640104144, 'expected' => TRUE, 'format' => 'withoutParam'],
            ['5' => 'min:5', 'value' => 5, 'expected' => TRUE, 'format' => 'withParam'],
            ['6' => 'max:10', 'value' => 10, 'expected' => TRUE, 'format' => 'withParam'],
            ['7' => 'string', 'value' => '', 'expected' => TRUE, 'format' => 'withoutParam'],
            ['8' => 'equal:10', 'value' => 10, 'expected' => TRUE, 'format' => 'withParam'],
            ['9' => 'equal:Test', 'value' => 'Test', 'expected' => TRUE, 'format' => 'withParam'],
            ['10' => 'between:10,20', 'value' => 20, 'expected' => TRUE, 'format' => 'withParam'],
            ['11' => 'reg:(^[1-9]$)', 'value' => '1', 'expected' => TRUE, 'format' => 'withParam'],
            ['12' => 'engchar', 'value' => 'English Chars', 'expected' => TRUE, 'format' => 'withoutParam'],
            ['13' => 'fachar', 'value' => 'کلمات فارسی', 'expected' => TRUE, 'format' => 'withoutParam'],
            ['14' => 'slug:-', 'value' => 'some-url-for-test', 'expected' => TRUE, 'format' => 'withParam'],
            ['15' => 'html', 'value' => '<p class="paragraph">This is a html tag</p>', 'expected' => TRUE, 'format' => 'withoutParam']
        ];
    }

    public function get_data_failed(): array
    {
        return [
            ['0' => 'required', 'value' => 1, 'expected' => TRUE, 'format' => 'withoutParam'],
            ['1' => 'nullable', 'value' => '', 'expected' => TRUE, 'format' => 'withoutParam'],
            ['2' => 'in:1,2,3', 'value' => 1, 'expected' => TRUE, 'format' => 'withParam'],
            ['3' => 'array', 'value' => [1, "Test", 2], 'expected' => TRUE, 'format' => 'withoutParam'],
            ['4' => 'ncode', 'value' => 2640104144, 'expected' => TRUE, 'format' => 'withoutParam'],
            ['5' => 'min:10', 'value' => '12', 'expected' => TRUE, 'format' => 'withParam'],
            ['6' => 'max:10', 'value' => 5, 'expected' => TRUE, 'format' => 'withParam'],
            ['7' => 'string', 'value' => '', 'expected' => TRUE, 'format' => 'withoutParam'],
            ['8' => 'equal:10', 'value' => 10, 'expected' => TRUE, 'format' => 'withParam'],
            ['9' => 'equal:Test', 'value' => 'Test-Base', 'expected' => TRUE, 'format' => 'withParam'],
            ['10' => 'between:10,20', 'value' => 15, 'expected' => TRUE, 'format' => 'withParam'],
            ['11' => 'reg:(^[1-9]{1}$)', 'value' => '1', 'expected' => TRUE, 'format' => 'withParam'],
            ['12' => 'engchar', 'value' => 'English Chars', 'expected' => TRUE, 'format' => 'withoutParam'],
            ['13' => 'fachar', 'value' => 'کلمات فارسی', 'expected' => TRUE, 'format' => 'withoutParam'],
            ['14' => 'slug:-', 'value' => 'some-url-slug', 'expected' => TRUE, 'format' => 'withParam'],
            ['15' => 'html', 'value' => '<p>This is a html tag</p>', 'expected' => TRUE, 'format' => 'withoutParam']
        ];
    }

    /**
     * @dataProvider get_data_success
     * @param string $rule
     * @param $value
     * @param bool $expected
     * @param string $format
     */
    public function testMain(string $rule, $value, bool $expected, string $format): void
    {
        $check_format = $this->check_rule_format($rule);
        $this->assertSame($check_format, $format);

        $this->assertSame($this->run_callable($rule, $value, $check_format), $expected);
    }

    private function check_rule_format($rule): string
    {
        $format = '';

        if (preg_match('/^[a-zA-Z]+$/', $rule)) {
            $format = 'withoutParam';
        } else if (preg_match('/^[a-zA-Z]+:[a-zA-Z0-9$^><,\/_\-}{\]\[=)(\\\]+$/', $rule)) {
            $format = 'withParam';
        }

        return $format;
    }

    private function run_callable($rule, $value, $check_format): bool
    {
        if ($check_format == 'withoutParam') {
            switch ($rule) {
                case 'required':
                    return $this->required($value);
                case 'nullable':
                    return TRUE;
                case 'array':
                    return $this->array($value);
                case 'ncode':
                    return $this->ncode($value);
                case 'string':
                    return $this->string($value);
                case 'engchar':
                    return $this->engchar($value);
                case 'fachar':
                    return $this->fachar($value);
                case 'html':
                    return $this->html($value);
            }
        } elseif ($check_format == 'withParam') {
            if (preg_match('/^in:(\w,?+|(-?[0-9]+|[1-9][0-9]*),?)+$/', $rule)) {
                return $this->in($value, $rule);

            } else if (preg_match('/^min:(-?[1-9]+|[0-9]+)+$/', $rule)) {
                return $this->min($value, $rule);

            } else if (preg_match('/^max:(-?[1-9]+|[0-9]+)+$/', $rule)) {
                return $this->max($value, $rule);

            } else if (preg_match('/^equal:\w+$/', $rule)) {
                return $this->equal($value, $rule);

            } else if (preg_match('/^between:(-?[1-9]+)|([0-9]+),(-?[1-9]+)|([0-9]+)$/', $rule)) {
                return $this->between($value, $rule);

            } else if (preg_match('/^reg:\(([a-zA-Z0-9}{\]\[^\$_\-.!?%&><*:)\s(\\\|=+\/@]+)\)$/', $rule)) {
                return $this->reg($value, $rule);

            } else if (preg_match('/^slug:[-_+]$/', $rule)) {
                return $this->slug($value, $rule);

            }
        }

        return FALSE;
    }

//    private function nullable($value): bool
//    {
//        return TRUE;
//    }

    private function required($value): bool
    {
        return $value ? TRUE : FALSE;
    }

    private function array($value): bool
    {
        return is_array($value);

    }

    private function ncode($value): bool
    {
        $code = (string)preg_replace('/[^0-9]/', '', $value);

        if (strlen($code) != 10) {
            return FALSE;
        }

        $list_code = str_split($code);
        $last = (int)$list_code[9];
        unset($list_code[9]);
        $i = 10;
        $sum = 0;

        foreach ($list_code as $key => $_) {
            $sum += intval($_) * $i--;
        }

        $mod = (int)$sum % 11;

        if ($mod >= 2) {
            $mod = 11 - $mod;
        }

        if ($mod == $last) {
            return TRUE;
        }
        return FALSE;
    }

    private function min($value, $rule): bool
    {
        $explode = explode(':', $rule);
        $min = $explode[1];
        return $value >= $min;
    }

    private function max($value, $rule): bool
    {
        $explode = explode(':', $rule);
        $max = $explode[1];
        return $value <= $max;

    }

    private function string($value): bool
    {
        return is_string((string)$value);
    }

    private function equal($value, $rule): bool
    {
        $explode = explode(':', $rule);
        $equal = $explode[1];
        return $value == $equal;

    }

    private function between($value, $rule): bool
    {
        $explode = explode(':', $rule);
        list($from, $to) = explode(',', $explode[1]);
        return $value >= $from && $value <= $to;
    }

    private function reg($value, $rule): bool
    {
        $explode = explode(':', $rule);
        $reg = substr($explode[1], 1, -1);
        return (bool)preg_match("/$reg/", $value);
    }

    private function in($value, $rule): bool
    {
        $explode = explode(':', $rule);
        $in = explode(',', $explode[1]);
        return in_array($value, $in);
    }

    private function engchar($value): bool
    {
        return (bool)preg_match("/^[\s@#$%^&*()_\-+A-Za-z0-9]+$/", $value);
    }

    private function fachar($value): bool
    {
        return (bool)preg_match("/^[\-|\sآابپتثجچحخدذرزژسشصضطظعغفقکگلمنوهی]+$/", $value);
    }

    private function html($value): bool
    {
        return (bool)preg_match('/^[a-zA-Z\"\'=><)(0-9\s\w@#$%^&*\-_+\]\[};:{~!\/?\\\]+$/', $value);
    }

    private function slug($value, $rule): bool
    {
        $explode = explode(':', $rule);
        $separator = trim($explode[1]);
        return (bool)preg_match("/^[a-zA-Z0-9]+(" . $separator . "[a-zA-Z0-9]+)*$/", $value);
    }

}
