<?php

/*
 * AJGL Doctrine ORM Functions
 *
 * Copyright (C) Antonio J. García Lagar <aj@garcialagar.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ajgl\Doctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * "B_OP" "(" ArithmeticPrimary "," StringPrimary "," ArithmeticPrimary ")".
 */
class BinaryOperator extends FunctionNode
{
    /** @var Node */
    public $first;

    /** @var Node */
    public $operator;

    /** @var Node */
    public $second;

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->first = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->operator = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->second = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        return sprintf('(%s %s %s)',
            $this->first->dispatch($sqlWalker),
            $this->operator->value,
            $this->second->dispatch($sqlWalker)
        );
    }
}
