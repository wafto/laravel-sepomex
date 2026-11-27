<?php

use Wafto\Sepomex\Support\DelimitedFileIterator;

function delimitedFile(string $contents): string
{
    $path = tempnam(sys_get_temp_dir(), 'sepomex');
    file_put_contents($path, $contents);

    return $path;
}

it('reads rows as associative arrays using the file headers', function () {
    $iterator = (new DelimitedFileIterator(delimitedFile("clause\na|b\n1|2\n3|4\n"), skipLines: 1))->readHeaders();

    expect(iterator_to_array($iterator, false))->toBe([
        ['a' => '1', 'b' => '2'],
        ['a' => '3', 'b' => '4'],
    ]);
});

it('skips blank lines instead of stopping', function () {
    $iterator = (new DelimitedFileIterator(delimitedFile("a|b\n1|2\n\n   \n3|4\n\n")))->readHeaders();

    expect(iterator_to_array($iterator, false))->toHaveCount(2);
});

it('keeps zero values and converts empty values to null', function () {
    $iterator = (new DelimitedFileIterator(delimitedFile("a|b|c\n0| |x\n")))->readHeaders();

    expect(iterator_to_array($iterator, false))->toBe([
        ['a' => '0', 'b' => null, 'c' => 'x'],
    ]);
});

it('throws on rows with a column count mismatch', function () {
    $iterator = (new DelimitedFileIterator(delimitedFile("clause\na|b\n1|2\n1|2|3\n"), skipLines: 1))->readHeaders();

    iterator_to_array($iterator);
})->throws(UnexpectedValueException::class, 'Line 4 has 3 columns, expected 2.');

it('throws when the encoding conversion fails', function () {
    $iterator = (new DelimitedFileIterator(delimitedFile("a|b\n\xFF|2\n"), inputEncoding: 'utf-8', outputEncoding: 'ascii'))->readHeaders();

    iterator_to_array($iterator);
})->throws(UnexpectedValueException::class, 'Unable to convert line 2 from utf-8 to ascii.');

it('does not skip the first row when headers are given manually', function () {
    $iterator = (new DelimitedFileIterator(delimitedFile("1|2\n3|4\n")))->withHeaders(['a', 'b']);

    expect(iterator_to_array($iterator, false))->toBe([
        ['a' => '1', 'b' => '2'],
        ['a' => '3', 'b' => '4'],
    ]);
});
