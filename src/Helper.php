<?php namespace ypppa\Model;

class Helper
{
    /**
     * Get the class "basename" of the given object / class.
     *
     * @param  string|object $class
     * @return string
     */
    public static function classBaseName($class)
    {
        $class = is_object($class) ? get_class($class) : $class;

        return basename(str_replace('\\', '/', $class));
    }

    /**
     * Returns all traits used by a class, its subclasses and trait of their traits.
     *
     * @param  string $class
     * @return array
     */
    public static function classUsesRecursive($class)
    {
        $results = [];
        foreach (array_merge([$class => $class], class_parents($class)) as $class) {
            $results += static::traitUsesRecursive($class);
        }

        return array_unique($results);
    }

    /**
     * Returns all traits used by a trait and its traits.
     *
     * @param  string $trait
     * @return array
     */
    public static function traitUsesRecursive($trait)
    {
        $traits = class_uses($trait);
        foreach ($traits as $trait) {
            $traits += static::traitUsesRecursive($trait);
        }

        return $traits;
    }

    /**
     * Convert a string to snake case.
     *
     * @param  string $value
     * @param  string $delimiter
     * @return string
     */
    public static function snake($value, $delimiter = '_')
    {
        if (!ctype_lower($value)) {
            $value = preg_replace('/\s+/', '', $value);
            $value = strtolower(preg_replace('/(.)(?=[A-Z])/', '$1' . $delimiter, $value));
        }

        return $value;
    }

    /**
     * Convert a value to studly caps case.
     *
     * @param  string $value
     * @param bool $lc_first
     * @return string
     */
    public static function studly($value, $lc_first = true)
    {
        $value = ucwords(str_replace(['-', '_'], ' ', $value));

        if ($lc_first) $value = lcfirst($value);

        return str_replace(' ', '', $value);
    }
}
