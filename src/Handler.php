<?hh // strict

namespace kilahm\AttributeRouter;

abstract class Handler<Tcontainer>
{
    protected Vector<string> $matches = Vector{};

    abstract public static function factory(Tcontainer $arg) : this;

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
