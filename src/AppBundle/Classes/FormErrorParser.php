<?php
namespace AppBundle\Classes;

use Symfony\Component\Form\Form;

/**
 * Class FormErrorParser
 * @package AppBundle\Classes
 */
class FormErrorParser
{
    /**
     * @param Form $form
     * @param bool $trigger
     * @param null $index
     * @return array
     *
     */
    public static function parse(Form $form, $trigger = true, $index = null)
    {
        $data = [];
        $messages = [];

        if ($trigger) {
            foreach ($form->getErrors() as $error) {
                $messages[] = $error->getMessage();
            }
        }

        /** @var Form $item */
        foreach($form->all() as $key => $item) {
            $itemData = [];
            $index = is_numeric($index) ? $index : $key;
            if ($item->all()) {
                $itemData = self::parse($item, false, $index);
            } else {
                foreach ($item->getErrors() as $err) {
                    $itemData[] = $err->getMessage();
                }
            }

            if ($itemData) {
                if (is_numeric($index)) {
                    $data = array_merge($data, $itemData);
                } else {
                    $data[$item->getName()] = $itemData;
                }
            }
        }

        if ($trigger) {
            return [
                'messages' => $messages,
                'errors' => $data
            ];
        } else {
            return $data;
        }
    }

}
