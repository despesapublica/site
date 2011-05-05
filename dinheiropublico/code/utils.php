<?php

define('CLEAN_INT', 'CLEAN_INT');
define('CLEAN_FLOAT', 'CLEAN_FLOAT');
define('CLEAN_NONE', 'CLEAN_NONE');
define('CLEAN_STRING', 'CLEAN_STRING');

//ADODB
function prepare_query($sql, $offset=-1, $nrows=-1, $inputarr=false) {
    $limit = '';
    if ($offset >= 0 || $nrows >= 0) {
        $offset = ($offset >= 0) ? $offset . "," : '';
        $nrows = ($nrows >= 0) ? $nrows : '18446744073709551615';
        $limit = ' LIMIT ' . $offset . ' ' . $nrows;
    }

    if ($inputarr && is_array($inputarr)) {
        $sqlarr = explode('?', $sql);

        if (sizeof($inputarr) + 1 != sizeof($sqlarr))
            return false;

        $sql = '';
        $i = 0;
        foreach ($inputarr as $v) {
            $sql .= $sqlarr[$i];
            switch (gettype($v)) {
                case 'string':
                    $sql .= '\'' . Convert::raw2sql($v) . '\'';
                    break;
                case 'double':
                    $sql .= str_replace(',', '.', $v);
                    break;
                case 'boolean':
                    $sql .= $v ? 1 : 0;
                    break;
                default:
                    if ($v === null)
                        $sql .= 'NULL';
                    else
                        $sql .= $v;
            }
            $i++;
        }
        $sql .= $sqlarr[$i];
    }
    return $sql . $limit;
}

function getParam($params, $name, $type = null, $defaultValue = null) {
    if (!key_exists($name, $params))
        return $defaultValue;

    switch ($type) {
        case 'CLEAN_INT':
            return (int) $params[$name];
            break;
        case 'CLEAN_FLOAT':
            return (float) str_replace(',', '.', $params[$name]);
            break;
        case 'CLEAN_NONE':
            return $params[$name];
            break;
        case 'CLEAN_STRING':
            return $params[$name];
            break;
        default:
            return $params[$name];
            break;
    } // switch
}

function convertArrayToViewableData($array, $fieldsType=null, $autoDetect = true) {
    $fieldType;
    $object = new ViewableData();

    if (empty($fieldsType))
        $fieldsType = array();

    if (is_array($array) && count($array) > 0) {
        foreach ($array as $name => $value) {
            $name = trim($name);
            $fieldType = null;
            $value_aux = $value;

            if (!empty($name) && $value !== null) {
                if (is_array($value)) {
                    $object->$name = convertArrayToViewableData($value, $fieldsType, $autoDetect);
                } else {
                    if($value !== '')
                    {
                        if (array_key_exists($name, $fieldsType)) {
                            $fieldType = $fieldsType[$name];
                        } else if ($autoDetect) {
                            if (stripos ($name, 'preco')===0 || stripos ($name, 'valor')===0)
                                $fieldType = 'Money';
                            else if (stripos ($name, 'data')===0) {
                                $fieldType = 'Date';
                            } else if (stripos ($name, 'numregisto')===0) {
                                $fieldType = 'IntPT';
                            }
                        }
                    }
                    if (!empty($fieldType)) {
                        if ($fieldType == 'Money') {
                            $value_aux = DBField::create($fieldType, array('Currency' => 'EUR', 'Amount' => str_replace(',', '.', $value_aux)));
                        }
                        else
                            $value_aux = DBField::create($fieldType, $value_aux);
                    }

                    $object->setField($name, $value_aux);
                }
            }
        }
    }
    return $object;
}

function formatMoney($number, $fractional=false) {
    if ($fractional) {
        $number = sprintf('%.2f', $number);
    }
    while (true) {
        $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number);
        if ($replaced != $number) {
            $number = $replaced;
        } else {
            break;
        }
    }
    return $number . ' €';
}

?>
