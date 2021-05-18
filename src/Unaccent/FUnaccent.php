<?php

/*
 * AJGL Doctrine ORM Functions
 *
 * Copyright (C) Antonio J. García Lagar <aj@garcialagar.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ajgl\Doctrine\ORM\Query\AST\Functions\Unaccent;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * "F_UNACCENT" "(" StringPrimary ")".
 *
 * @see https://stackoverflow.com/a/11007216/348403
 */
class FUnaccent extends FunctionNode
{
    /** @var Node */
    public $argument;

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->argument = $parser->StringPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        return 'F_UNACCENT('.$this->argument->dispatch($sqlWalker).')';
    }
}
