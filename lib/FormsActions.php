<?php
/**
 * Actions related to forms.
 * @author Nelly Todorova <nelly.todorova@yahoo.com>
 */
class FormsActions
{
    /**
     * Verify the fields and return an array with each field's errors.
     * @param array $fieldsConfig - configuration of all fields
     * @return array
     */
    public static function verifyFields($fieldsConfig)
    {
        if (empty($fieldsConfig)) {
            trigger_error('Custom error: No relevant fields configuration set!', E_USER_ERROR);
        }

        $verify = array();

        foreach ($fieldsConfig as $field => $config) {
            if (((int)$config['notEmpty'] == 1 && empty($_POST[$field]))
                    || (!empty($config['minSymbols']) && mb_strlen($_POST[$field]) < (int)$config['minSymbols'])
                    || (!empty($config['maxSymbols']) && mb_strlen($_POST[$field]) > (int)$config['maxSymbols'])
                    || (!empty($config['valimanSymbolsdateRegEx']) && !filter_var($_POST[$field], FILTER_VALIDATE_EMAIL))
                    ) {
                $verify[$field] = $config['errorMessage'];
            }
        }

        return $verify;
    }
}
?>