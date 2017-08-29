<?php
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 8/29/17
 * Time: 4:39 PM
 */

namespace Psy\TabCompletion\Matcher;


class ClassDoubleColonMatcher extends AbstractMatcher
{

    /**
     * {@inheritdoc}
     */
    public function getMatches(array $tokens, array $info = array())
    {
        $class = $this->getNamespaceAndClass($tokens);
        if (strlen($class) > 0 && $class[0] === '\\') {
            $class = substr($class, 1, strlen($class));
        }
        $quotedClass = preg_quote($class);

        return array_map(
            function ($className) use ($class) {
                // get the number of namespace separators
                $nsPos = substr_count($class, '\\');
                $pieces = explode('\\', $className);
                //$methods = Mirror::get($class);
                return implode('\\', array_slice($pieces, $nsPos, count($pieces))) . '::';
            },
            array_filter(
                get_declared_classes(),
                function ($className) use ($quotedClass) {
                    return $className == $quotedClass;
                }
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function hasMatched(array $tokens)
    {
        $token = array_pop($tokens);

        switch(true) {
            case self::tokenIs($token, self::T_STRING):
                return true;
        }

        return false;
    }
}