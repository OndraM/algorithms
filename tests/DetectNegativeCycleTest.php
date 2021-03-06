<?php

namespace Graphp\Tests\Algorithms;

use Graphp\Algorithms\DetectNegativeCycle;
use Graphp\Graph\Graph;

class DetectNegativeCycleTest extends TestCase
{
    public function testNullGraph()
    {
        $graph = new Graph();

        $alg = new DetectNegativeCycle($graph);

        $this->assertFalse($alg->hasCycleNegative());

        return $alg;
    }

    /**
     *
     * @param DetectNegativeCycle $alg
     * @depends testNullGraph
     */
    public function testNullGraphHasNoCycle(DetectNegativeCycle $alg)
    {
        $this->setExpectedException('UnderflowException');
        $alg->getCycleNegative();
    }

    /**
     *
     * @param DetectNegativeCycle $alg
     * @depends testNullGraph
     */
    public function testNullGraphHasNoCycleGraph(DetectNegativeCycle $alg)
    {
        $this->setExpectedException('UnderflowException');
        $alg->createGraph();
    }

    public function testNegativeLoop()
    {
        // 1 --[-1]--> 1
        $graph = new Graph();
        $v1 = $graph->createVertex(1);
        $e1 = $graph->createEdgeDirected($v1, $v1)->setWeight(-1);

        $alg = new DetectNegativeCycle($graph);

        $this->assertTrue($alg->hasCycleNegative());

        $cycle = $alg->getCycleNegative();

        $this->assertCount(1, $cycle->getEdges());
        $this->assertCount(2, $cycle->getVertices());
        $this->assertEquals($e1, $cycle->getEdges()->getEdgeFirst());
        $this->assertEquals($v1, $cycle->getVertices()->getVertexFirst());
    }

    public function testNegativeCycle()
    {
        // 1 --[-1]--> 2
        // ^           |
        // \---[-2]----/
        $graph = new Graph();
        $v1 = $graph->createVertex(1);
        $v2 = $graph->createVertex(2);
        $graph->createEdgeDirected($v1, $v2)->setWeight(-1);
        $graph->createEdgeDirected($v2, $v1)->setWeight(-2);

        $alg = new DetectNegativeCycle($graph);

        $this->assertTrue($alg->hasCycleNegative());

        $cycle = $alg->getCycleNegative();

        $this->assertCount(2, $cycle->getEdges());
        $this->assertCount(3, $cycle->getVertices());
    }

    public function testNegativeUndirectedIsNegativeCycle()
    {
        // 1 --[-1]-- 2
        $graph = new Graph();
        $v1 = $graph->createVertex(1);
        $v2 = $graph->createVertex(2);
        $graph->createEdgeUndirected($v1, $v2)->setWeight(-1);

        $alg = new DetectNegativeCycle($graph);

        $this->assertTrue($alg->hasCycleNegative());

        $cycle = $alg->getCycleNegative();

        $this->assertCount(2, $cycle->getEdges());
        $this->assertCount(3, $cycle->getVertices());
    }

    public function testNegativeCycleSubgraph()
    {
        // 1 --[1]--> 2 --[1]--> 3 --[1]--> 4
        //                       ^          |
        //                       \---[-2]---/
        $graph = new Graph();
        $v1 = $graph->createVertex(1);
        $v2 = $graph->createVertex(2);
        $v3 = $graph->createVertex(3);
        $v4 = $graph->createVertex(4);
        $graph->createEdgeDirected($v1, $v2)->setWeight(1);
        $graph->createEdgeDirected($v2, $v3)->setWeight(1);
        $graph->createEdgeDirected($v3, $v4)->setWeight(1);
        $graph->createEdgeDirected($v4, $v3)->setWeight(-2);

        $alg = new DetectNegativeCycle($graph);

        $this->assertTrue($alg->hasCycleNegative());

        $cycle = $alg->getCycleNegative();

        $this->assertCount(2, $cycle->getEdges());
        $this->assertCount(3, $cycle->getVertices());
        $this->assertTrue($cycle->getVertices()->hasVertexId(3));
        $this->assertTrue($cycle->getVertices()->hasVertexId(4));
    }

    public function testNegativeComponents()
    {
        // 1 -- 2     3 --[-1]--> 4
        //            ^           |
        //            \---[-2]----/
        $graph = new Graph();
        $v1 = $graph->createVertex(1);
        $v2 = $graph->createVertex(2);
        $v3 = $graph->createVertex(3);
        $v4 = $graph->createVertex(4);
        $graph->createEdgeUndirected($v1, $v2);
        $graph->createEdgeDirected($v3, $v4)->setWeight(-1);
        $graph->createEdgeDirected($v4, $v3)->setWeight(-2);

        $alg = new DetectNegativeCycle($graph);

        $this->assertTrue($alg->hasCycleNegative());

        $cycle = $alg->getCycleNegative();

        $this->assertCount(2, $cycle->getEdges());
        $this->assertCount(3, $cycle->getVertices());
        $this->assertTrue($cycle->getVertices()->hasVertexId(3));
        $this->assertTrue($cycle->getVertices()->hasVertexId(4));
    }
}
