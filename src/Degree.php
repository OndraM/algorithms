<?php

namespace Fhaculty\Graph\Algorithm;

use Fhaculty\Graph\Algorithm\Base;
use Fhaculty\Graph\Graph;
use Fhaculty\Graph\Vertex;
use Fhaculty\Graph\Exception\UnexpectedValueException;

class Degree extends Base
{
    private $graph;

    public function __construct(Graph $graph)
    {
        $this->graph = $graph;
    }

    /**
     * get degree for k-regular-graph (only if each vertex has the same degree)
     *
     * @return int
     * @throws UnderflowException       if graph is empty
     * @throws UnexpectedValueException if graph is not regular (i.e. vertex degrees are not equal)
     * @uses Vertex::getDegree()
     */
    public function getDegree()
    {
        // get initial degree of any start vertex to compare others to
        $degree = $this->graph->getVertexFirst()->getDegree();

        foreach ($this->graph->getVertices() as $vertex) {
            /** @var $vertex Vertex */
            $i = $vertex->getDegree();

            if ($i !== $degree) {
                throw new UnexpectedValueException('Graph is not k-regular (vertex degrees differ)');
            }
        }

        return $degree;
    }

    /**
     * get minimum degree of vertices
     *
     * @return int
     * @throws Exception if graph is empty or directed
     * @uses Vertex::getFirst()
     * @uses Vertex::getDegree()
     */
    public function getDegreeMin()
    {
        return Vertex::getFirst($this->graph->getVertices(), Vertex::ORDER_DEGREE)->getDegree();
    }

    /**
     * get maximum degree of vertices
     *
     * @return int
     * @throws Exception if graph is empty or directed
     * @uses Vertex::getFirst()
     * @uses Vertex::getDegree()
     */
    public function getDegreeMax()
    {
        return Vertex::getFirst($this->graph->getVertices(), Vertex::ORDER_DEGREE, true)->getDegree();
    }

    /**
     * checks whether this graph is regular, i.e. each vertex has the same indegree/outdegree
     *
     * @return boolean
     * @uses Graph::getDegree()
     */
    public function isRegular()
    {
        // an empty graph is considered regular
        if ($this->graph->isEmpty()) {
            return true;
        }
        try {
            $this->getDegree();

            return true;
        } catch (UnexpectedValueException $ignore) { }

        return false;
    }
}