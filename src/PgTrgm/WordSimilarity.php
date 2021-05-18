<?php

/*
 * AJGL Doctrine ORM Functions
 *
 * Copyright (C) Antonio J. García Lagar <aj@garcialagar.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ajgl\Doctrine\ORM\Query\AST\Functions\PgTrgm;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * "WORD_SIMILARITY" "(" StringPrimary "," StringPrimary ")".
 */
class WordSimilarity extends FunctionNode
{
    /** @var Node */
    public $first;

    /** @var Node */
    public $second;

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->first = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->second = $parser->StringPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        return
            'WORD_SIMILARITY('.
            $this->first->dispatch($sqlWalker).', '.
            $this->second->dispatch($sqlWalker).
            ')'
        ;
    }
}
