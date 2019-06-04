<?php


namespace App\Service;

class Slugify
{
    function remove_accent($str)
    {
        $characters = array(
            '/[áàâãªä]/u'   =>   'a',
            '/[ÁÀÂÃÄ]/u'    =>   'A',
            '/[ÍÌÎÏ]/u'     =>   'I',
            '/[íìîï]/u'     =>   'i',
            '/[éèêë]/u'     =>   'e',
            '/[ÉÈÊË]/u'     =>   'E',
            '/[óòôõºö]/u'   =>   'o',
            '/[ÓÒÔÕÖ]/u'    =>   'O',
            '/[úùûü]/u'     =>   'u',
            '/[ÚÙÛÜ]/u'     =>   'U',
            '/ç/'           =>   'c',
            '/Ç/'           =>   'C',
            '/ñ/'           =>   'n',
            '/Ñ/'           =>   'N',
        );
        return preg_replace(array_keys($characters), array_values($characters), $str);
    }
    public function generate($slug)
    {
        $slugified = strtolower(preg_replace(
            array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'),
            array('', '-', ''), $this->remove_accent($slug)));
        return $slugified;
    }
}