<x-app-layout>
    <x-page-title page="Organograma" header="Organograma de Cargos" />

    <style>
        .tree {
            overflow-x: auto;
            overflow-y: hidden;
            width: 100%;
            padding-bottom: 20px;
        }

        .tree ul {
            padding-top: 20px;
            position: relative;
            list-style-type: none;
            white-space: nowrap;
        }

        .tree li {
            display: inline-block;
            text-align: center;
            position: relative;
            padding: 20px 5px 0 5px;
            vertical-align: top;
            white-space: normal;
        }

        .tree li::before,
        .tree li::after {
            content: '';
            position: absolute;
            top: 0;
            width: 50%;
            height: 20px;
            border-top: 1px solid #ccc;
            z-index: -1;
        }

        .tree li::before {
            right: 50%;
            border-right: 1px solid #ccc;
        }

        .tree li::after {
            left: 50%;
            border-left: 1px solid #ccc;
        }

        .tree li:only-child::before,
        .tree li:only-child::after,
        .tree li:first-child::before,
        .tree li:last-child::after {
            display: none;
        }

        .tree ul::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            border-left: 1px solid #ccc;
            width: 0;
            height: 20px;
            z-index: -1;
        }

        .tree .node {
            display: inline-block;
            border: 1px solid #ccc;
            padding: 10px 15px;
            border-radius: 8px;
            background: #f9f9f9;
            min-width: 160px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .node strong {
            font-size: 16px;
            display: block;
            margin-bottom: 2px;
        }

        .node em {
            display: block;
            font-size: 13px;
            color: #888;
        }
    </style>

    @php
        $nodesArray = json_decode($nodes, true);

        function buildTree($elements, $parentId = null) {
            $branch = [];
            foreach ($elements as $element) {
                if ($element['pid'] === $parentId) {
                    $children = buildTree($elements, $element['id']);
                    if ($children) {
                        $element['children'] = $children;
                    }
                    $branch[] = $element;
                }
            }
            return $branch;
        }

        function renderTree($tree) {
            echo '<ul>';
            foreach ($tree as $node) {
                echo '<li>';
                echo '<div class="node">';
                echo '<strong>' . htmlspecialchars($node['name']) . '</strong>';

                if (!empty($node['users'])) {
                    echo '<em>' . implode('<br>', array_map('htmlspecialchars', $node['users'])) . '</em>';
                }

                echo '</div>';

                if (isset($node['children'])) {
                    renderTree($node['children']);
                }

                echo '</li>';
            }
            echo '</ul>';
        }

        $tree = buildTree($nodesArray);
    @endphp

    <div class="tree">
        @php renderTree($tree); @endphp
    </div>
</x-app-layout>
