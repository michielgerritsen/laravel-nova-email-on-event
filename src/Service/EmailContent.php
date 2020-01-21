<?php
/**
 *    ______            __             __
 *   / ____/___  ____  / /__________  / /
 *  / /   / __ \/ __ \/ __/ ___/ __ \/ /
 * / /___/ /_/ / / / / /_/ /  / /_/ / /
 * \______________/_/\__/_/   \____/_/
 *    /   |  / / /_
 *   / /| | / / __/
 *  / ___ |/ / /_
 * /_/ _|||_/\__/ __     __
 *    / __ \___  / /__  / /____
 *   / / / / _ \/ / _ \/ __/ _ \
 *  / /_/ /  __/ /  __/ /_/  __/
 * /_____/\___/_/\___/\__/\___/
 *
 */

namespace MichielGerritsen\LaravelNovaEmailOnEvent\Service;

use Illuminate\Support\Arr;

class EmailContent
{
    /**
     * @var array
     */
    private $variables;

    public function prepare(string $message, $event)
    {
        $this->variables = get_object_vars($event);

        return preg_replace_callback('#{(.*?)}#', [$this, 'replaceVariable'], $message);
    }

    private function replaceVariable($match)
    {
        $parts = explode('->', str_replace('-&gt;', '->', $match[1]));

        $first = substr(Arr::first($parts), 1);

        if (!isset($this->variables[$first])) {
            return null;
        }

        $value = $this->variables[$first];
        foreach (array_splice($parts, 1) as $part) {
            if (is_array($value) && array_key_exists($part, $value)) {
                $value = $value[$part];
                continue;
            }

            if (is_object($value) && property_exists($value, $part)) {
                $value = $value->$part;
                continue;
            }

            break;
        }

        return $value;
    }
}
