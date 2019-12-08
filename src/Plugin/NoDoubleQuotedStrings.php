<?php

namespace SamLitowitz\Psalm\Plugin;

use PhpParser\Node\Expr;
use PhpParser\Node\Scalar\String_;
use Psalm\Codebase;
use Psalm\CodeLocation;
use Psalm\Context;
use Psalm\FileManipulation;
use Psalm\IssueBuffer;
use Psalm\Plugin\Hook\AfterExpressionAnalysisInterface;
use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\RegistrationInterface;
use Psalm\StatementsSource;
use SamLitowitz\Psalm\Issue\DoubleQuotedString;
use SimpleXMLElement;

final class NoDoubleQuotedStrings implements PluginEntryPointInterface, AfterExpressionAnalysisInterface
{
    /**
     * @inheritDoc
     */
    public function __invoke(RegistrationInterface $api, SimpleXMLElement $config = null)
    {
        // TODO: Implement __invoke() method.
    }

    /**
     * @inheritDoc
     */
    public static function afterExpressionAnalysis(
        Expr $expr,
        Context $context,
        StatementsSource $statements_source,
        Codebase $codebase,
        array &$file_replacements = []
    ) {
        if ($expr instanceof String_) {
            $kind = $expr->getAttribute('kind');

            switch ($kind) {
                case String_::KIND_DOUBLE_QUOTED:
                    if (IssueBuffer::accepts(
                        new DoubleQuotedString(
                            'Use single quotes',
                            new CodeLocation($statements_source->getSource(), $expr)
                        ),
                        $statements_source->getSuppressedIssues()
                    )) {
                        return null;
                    }
                    break;
                case String_::KIND_HEREDOC:
                    if (IssueBuffer::accepts(
                        new DoubleQuotedString(
                            'Use nowdoc',
                            new CodeLocation($statements_source->getSource(), $expr)
                        ),
                        $statements_source->getSuppressedIssues()
                    )) {
                        return null;
                    }
                    break;
                case String_::KIND_SINGLE_QUOTED:
                case String_::KIND_NOWDOC:
                default:
            }
        }
        return null;
    }
}