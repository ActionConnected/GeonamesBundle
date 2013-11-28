<?php
namespace Giosh94mhz\GeonamesBundle\Doctrine\MySql;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Giosh94mhz\GeonamesBundle\Model\Measure;

/**
 * Usage: LONGITUDE_WITHIN(longitude, center, distanceInKm)
 * Usage: LONGITUDE_WITHIN(longitude, [latitude, ]center, distanceInKm)
 * if latitude is not specified,
 * Returns: boolean
 *
 * @author Premi Giorgio <giosh94mhz@gmail.com>
 */
class LongitudeWithin extends FunctionNode
{
    protected $longitude;

    protected $latitude;

    protected $center;

    protected $distance;

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->longitude = $parser->ArithmeticExpression();
        $parser->match(Lexer::T_COMMA);
        $this->center = $parser->ArithmeticExpression();
        $parser->match(Lexer::T_COMMA);
        $this->distance = $parser->ArithmeticExpression();
        if ($parser->getLexer()->isNextToken(Lexer::T_COMMA)) {
            $parser->match(Lexer::T_COMMA);
            $this->latitude = $this->center;
            $this->center = $this->distance;
            $this->distance = $parser->ArithmeticExpression();
        }
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        return $this->latitude? $this->getSqlWithLatitude($sqlWalker) : $this->getSqlWithoutLatitude($sqlWalker);
    }

    private function getSqlWithoutLatitude(SqlWalker $sqlWalker)
    {
        return sprintf(
            '(%s BETWEEN (%s - %s / %F) AND (%s + %s / %F))',
            $sqlWalker->walkArithmeticPrimary($this->longitude),
            $sqlWalker->walkArithmeticPrimary($this->center),
            $sqlWalker->walkArithmeticPrimary($this->distance),
            Measure::RADIANS_TO_KM,
            $sqlWalker->walkArithmeticPrimary($this->center),
            $sqlWalker->walkArithmeticPrimary($this->distance),
            $sqlWalker->walkArithmeticPrimary($this->longitude),
            Measure::RADIANS_TO_KM
        );
    }

    private function getSqlWithLatitude(SqlWalker $sqlWalker)
    {
        return sprintf(
            '(%s BETWEEN (%s -(%s / ABS(COS(%s) * %F))) AND (%s + (%s / ABS(COS(%s) * %F))))',
            $sqlWalker->walkArithmeticPrimary($this->longitude),
            $sqlWalker->walkArithmeticPrimary($this->center),
            $sqlWalker->walkArithmeticPrimary($this->distance),
            $sqlWalker->walkArithmeticPrimary($this->latitude),
            Measure::RADIANS_TO_KM,
            $sqlWalker->walkArithmeticPrimary($this->center),
            $sqlWalker->walkArithmeticPrimary($this->distance),
            $sqlWalker->walkArithmeticPrimary($this->latitude),
            Measure::RADIANS_TO_KM
        );
    }
}
