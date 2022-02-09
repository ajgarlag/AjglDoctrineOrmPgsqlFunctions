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
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * "CAST" "(" ArithmeticExpression "," StringPrimary ")".
 *
 * @copyright 2018-2020 Dayan C. Galiazzi
 * @see https://gist.github.com/galiazzi/5e5f04f9753ba4d8a9b972c87dc2a805
 */
class Cast extends FunctionNode
{
    private $expr1;
    private $expr2;

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->expr1 = $parser->ArithmeticExpression();
        $parser->match(Lexer::T_COMMA);
        $this->expr2 = $parser->StringPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        $type = trim($this->expr2->dispatch($sqlWalker), "'");
        return sprintf(
            'cast(%s as %s)',
            $this->expr1->dispatch($sqlWalker),
            $type
        );
    }
}
