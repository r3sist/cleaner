<?php declare(strict_types=1);
/**
 * (c) resist
 */

namespace resist\Cleaner;

/**
 * Plain string sanitizer
 */
class Cleaner
{
    public const MARKDOWN_TAGS_INLINE = ['i', 'em', 'b', 'strong', 'a', 'code'];
    public const MARKDOWN_TAGS_BLOCK = ['i', 'em', 'b', 'strong', 'a', 'code', 'ul', 'li', 'p', 'ol', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'hr', 'img', 'blockquote'];

    /**
     * Trims, strips tags, and removes non-printable characters
     * @param string[] $enabledTags list of tag names without < and >
     * @see Use Symfony's html-sanitizer if possible
     */
    public function clean(string|int|float $string, array $enabledTags = []): string
    {
        $tagList = '';
        if (!empty($enabledTags)) {
            $tagList = '<' . implode('><', $enabledTags) . '>';
        }

        return trim(strip_tags(preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/','',(string)$string), $tagList));
    }
}
