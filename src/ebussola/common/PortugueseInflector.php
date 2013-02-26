<?php

namespace ebussola\common;

/**
 * User: Leonardo Shinagawa
 * Date: 25/02/13
 * Time: 21:03
 */
class PortugueseInflector extends \ebussola\akelos\Inflector {

    /**
     *    Regras de singularização/pluralização
     */
    static private $rules = array(
        //singular     plural
        'ão'    => 'ões',
        'ês'    => 'eses',
        'm'     => 'ns',
        'l'     => 'is',
        'r'     => 'res',
        'x'     => 'xes',
        'z'     => 'zes',
    );

    /**
     *    Exceções às regras
     */
    static private $exceptions = array(
        'cidadão' => 'cidadãos',
        'mão'     => 'mãos',
        'qualquer'=> 'quaisquer',
        'campus'  => 'campi',
        'lápis'   => 'lápis',
        'ônibus'  => 'ônibus',
    );

    /**
     *    Passa uma palavra para o plural
     *
     *    @param  string $word A palavra que deseja ser passada para o plural
     *    @return string
     */
    public function pluralize($word){
        //Pertence a alguma exceção?
        if(array_key_exists($word, self::$exceptions)):
            return self::$exceptions[$word];
        //Não pertence a nenhuma exceção. Mas tem alguma regra?
        else:
            foreach(self::$rules as $singular=>$plural):
                if(preg_match("({$singular}$)", $word))
                    return preg_replace("({$singular}$)", $plural, $word);
            endforeach;
        endif;
        //Não pertence às exceções, nem às regras.
        //Se não terminar com "s", adiciono um.
        if(substr($word, -1) !== 's')
            return $word . 's';
        return $word;
    }

    /**
     *    Passa uma palavra para o singular
     *
     *    @param  string $word A palavra que deseja ser passada para o singular
     *    @return string
     */
    public function singularize($word){
        //Pertence às exceções?
        if(in_array($word, self::$exceptions)):
            $invert = array_flip(self::$exceptions);
            return $invert[$word];
        //Não é execeção.. Mas pertence a alguma regra?
        else:
            foreach(self::$rules as $singular => $plural):
                if(preg_match("({$plural}$)",$word))
                    return preg_replace("({$plural}$)", $singular, $word);
            endforeach;
        endif;
        //Nem é exceção, nem tem regra definida.
        //Apaga a última somente se for um "s" no final
        if(substr($word, -1) == 's')
            return substr($word, 0, -1);
        return $word;
    }

}
