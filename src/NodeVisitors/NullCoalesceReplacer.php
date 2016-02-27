<?php

namespace Spatie\Php7to5\NodeVisitors;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Name;
use PhpParser\NodeVisitorAbstract;

class NullCoalesceReplacer extends NodeVisitorAbstract
{
    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node)
    {
        if (!$node instanceof Coalesce) {
            return;
        }

        $issetCall = new FuncCall(new Name('isset'), [$node->left]);

        return new Ternary($issetCall, $node->left, $node->right);
    }
}
