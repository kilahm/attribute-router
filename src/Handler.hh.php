<?hh // strict

namespace kilahm\AttributeRouter;

abstract class Handler
{
    private Vector<string> $matches;

    abstract public static function factory(mixed $arg) : this;

    public function setMatches(Vector<string> $matches) : this
    {
        $this->matches = $matches;
        return $this;
    }

    public function getMatches() : Vector<string>
    {
        return $this->matches;
    }
}
