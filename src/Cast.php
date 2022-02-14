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
 * "CAST" "(" ArithmeticExpression "AS" type ")".
 *
 * Original implementation from oro/doctrine-extensions under MIT license
 *
 * @copyright 2022, Antonio J. García Lagar <aj@garcialagar.es>
 * @copyright 2020, Oro, Inc.
 * @see https://github.com/oroinc/doctrine-extensions/blob/67eda39d5e94fa25d105c759673bc45a5256798f/src/Oro/ORM/Query/AST/Functions/Cast.php
 */
class Cast extends FunctionNode
{
    /** @var Node */
    public $expression;

    /** @var string */
    public $type;

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);

        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->expression = $parser->ArithmeticExpression();

        $parser->match(Lexer::T_AS);

        $parser->match(Lexer::T_IDENTIFIER);
        $lexer = $parser->getLexer();
        $this->type = $lexer->token['value'];
        if ($lexer->isNextToken(Lexer::T_OPEN_PARENTHESIS)) {
            $parser->match(Lexer::T_OPEN_PARENTHESIS);
            $parameter = $parser->Literal();
            $parameters = [
                $parameter->value
            ];
            if ($lexer->isNextToken(Lexer::T_COMMA)) {
                while ($lexer->isNextToken(Lexer::T_COMMA)) {
                    $parser->match(Lexer::T_COMMA);
                    $parameter = $parser->Literal();
                    $parameters[] = $parameter->value;
                }
            }
            $parser->match(Lexer::T_CLOSE_PARENTHESIS);
            $this->type .= '(' . \implode(', ', $parameters) . ')';
        }

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        return sprintf('cast(%s as %s)',
            $this->expression->dispatch($sqlWalker),
            $this->type
        );
    }
}
