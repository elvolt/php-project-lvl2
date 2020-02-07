<?php

namespace Differ\GenDiff;

function getAbsolutePath(string $file): string
{
    if (file_exists(getcwd() . "/{file}")) {
        return getcwd() . "/{file}";
    } else {
        return $file;
    }
}

function genDiff(string $firstFile, string $secondFile)
{
    $file1Path = getAbsolutePath($firstFile);
    $file2Path = getAbsolutePath($secondFile);

    $beforeJSON = file_get_contents($file1Path);
    $afterJSON = file_get_contents($file2Path);

    $before = json_decode($beforeJSON, true);
    $after = json_decode($afterJSON, true);

    $union = array_merge($before, $after);

    $changes = array_map(function ($key, $value) use ($before, $after) {
        $item = is_bool($value) ? var_export($value, true) : $value;

        // added
        if (!isset($before[$key])) {
            return "\t+ {$key}: {$item}";
        }
        // deleted
        if (!isset($after[$key])) {
            return "\t- {$key}: {$item}";
        }
        // unchanged
        if ($item === $before[$key]) {
            return "\t  {$key}: {$item}";
        }
        // changed
        if ($item !== $before[$key]) {
            return "\t+ {$key}: {$item}" . PHP_EOL . "\t- {$key}: {$before[$key]}";
        }
    }, array_keys($union), $union);

    $output = implode("\n", $changes);
    $result = "{" . PHP_EOL . "{$output}" . PHP_EOL . "}" . PHP_EOL;

    return $result;
}
