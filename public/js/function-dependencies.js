function initDependencyGraph(container, data, wire) {
    // 컨테이너 설정
    const containerElement = d3.select(container);
    const containerRect = containerElement.node().getBoundingClientRect();
    const width = containerRect.width || 800;
    const height = containerRect.height || 600;
    
    // 로딩 상태 제거
    d3.select('#graph-loading').style('display', 'none');
    
    // 기존 SVG 제거
    containerElement.select('svg').remove();
    
    // SVG 생성
    const svg = containerElement
        .append('svg')
        .attr('width', '100%')
        .attr('height', '100%')
        .attr('viewBox', `0 0 ${width} ${height}`)
        .style('background', '#fafafa');

    // 줌 그룹 생성
    const g = svg.append('g');
    
    // 줌 동작 정의
    const zoom = d3.zoom()
        .scaleExtent([0.1, 4])
        .on('zoom', function(event) {
            g.attr('transform', event.transform);
        });
    
    svg.call(zoom);

    // 그래프 데이터 복사
    let nodes = data.nodes.map(d => ({...d}));
    let links = data.links.map(d => ({...d}));

    // 화살표 마커 정의
    svg.append('defs').selectAll('marker')
        .data(['arrow'])
        .enter().append('marker')
        .attr('id', d => d)
        .attr('viewBox', '0 -5 10 10')
        .attr('refX', 25)
        .attr('refY', 0)
        .attr('markerWidth', 6)
        .attr('markerHeight', 6)
        .attr('orient', 'auto')
        .append('path')
        .attr('d', 'M0,-5L10,0L0,5')
        .attr('fill', '#666');

    // Force simulation 생성
    const simulation = d3.forceSimulation(nodes)
        .force('link', d3.forceLink(links).id(d => d.id).distance(120).strength(0.8))
        .force('charge', d3.forceManyBody().strength(-400))
        .force('center', d3.forceCenter(width / 2, height / 2))
        .force('collision', d3.forceCollide().radius(30));

    // 링크 그리기
    const link = g.append('g')
        .attr('class', 'links')
        .selectAll('line')
        .data(links)
        .enter()
        .append('line')
        .attr('stroke', '#999')
        .attr('stroke-opacity', 0.6)
        .attr('stroke-width', 2)
        .attr('marker-end', 'url(#arrow)');

    // 노드 그룹 생성
    const nodeGroup = g.append('g')
        .attr('class', 'nodes')
        .selectAll('g')
        .data(nodes)
        .enter()
        .append('g')
        .style('cursor', 'pointer')
        .call(d3.drag()
            .on('start', dragstarted)
            .on('drag', dragged)
            .on('end', dragended));

    // 노드 원 그리기
    const nodeCircles = nodeGroup
        .append('circle')
        .attr('r', d => {
            const dependentCount = links.filter(l => l.target.id === d.id || l.target === d.id).length;
            return Math.max(8, Math.min(20, 8 + dependentCount * 2));
        })
        .attr('fill', d => getCategoryColor(d.category))
        .attr('stroke', '#fff')
        .attr('stroke-width', 2)
        .on('click', function(event, d) {
            event.stopPropagation();
            wire.selectFunction(d.id);
            highlightNode(d.id);
        })
        .on('mouseover', function(event, d) {
            d3.select(this)
                .transition()
                .duration(200)
                .attr('stroke-width', 3)
                .attr('stroke', '#333');
            
            // 툴팁 표시
            showTooltip(event, d);
        })
        .on('mouseout', function(event, d) {
            d3.select(this)
                .transition()
                .duration(200)
                .attr('stroke-width', 2)
                .attr('stroke', '#fff');
            
            hideTooltip();
        });

    // 노드 라벨 그리기
    const nodeLabels = nodeGroup
        .append('text')
        .text(d => d.name)
        .attr('font-size', 11)
        .attr('font-weight', 'bold')
        .attr('fill', '#333')
        .attr('text-anchor', 'middle')
        .attr('dy', 4)
        .style('pointer-events', 'none')
        .style('user-select', 'none');

    // 시뮬레이션 틱 이벤트
    simulation.on('tick', () => {
        link
            .attr('x1', d => d.source.x)
            .attr('y1', d => d.source.y)
            .attr('x2', d => d.target.x)
            .attr('y2', d => d.target.y);

        nodeGroup
            .attr('transform', d => `translate(${d.x},${d.y})`);
    });

    // 드래그 함수들
    function dragstarted(event, d) {
        if (!event.active) simulation.alphaTarget(0.3).restart();
        d.fx = d.x;
        d.fy = d.y;
    }

    function dragged(event, d) {
        d.fx = event.x;
        d.fy = event.y;
    }

    function dragended(event, d) {
        if (!event.active) simulation.alphaTarget(0);
        d.fx = null;
        d.fy = null;
    }

    // 카테고리별 색상
    function getCategoryColor(category) {
        const colors = {
            'data-management': '#3B82F6',
            'authentication': '#EF4444',
            'api': '#10B981',
            'data': '#F59E0B',
            'utility': '#8B5CF6',
            'external': '#94A3B8',
            'default': '#6B7280'
        };
        return colors[category] || colors.default;
    }

    // 노드 하이라이트
    function highlightNode(functionId) {
        // 모든 요소를 흐리게
        nodeCircles.style('opacity', d => d.id === functionId ? 1 : 0.2);
        nodeLabels.style('opacity', d => d.id === functionId ? 1 : 0.2);
        
        // 관련 링크만 강조
        link.style('opacity', d => {
            const isRelated = d.source.id === functionId || d.target.id === functionId;
            return isRelated ? 1 : 0.1;
        });
        
        // 연결된 노드들도 강조
        const connectedNodes = new Set([functionId]);
        links.forEach(link => {
            if (link.source.id === functionId || link.source === functionId) {
                connectedNodes.add(link.target.id || link.target);
            }
            if (link.target.id === functionId || link.target === functionId) {
                connectedNodes.add(link.source.id || link.source);
            }
        });
        
        nodeCircles.style('opacity', d => connectedNodes.has(d.id) ? 1 : 0.2);
        nodeLabels.style('opacity', d => connectedNodes.has(d.id) ? 1 : 0.2);
    }

    // 하이라이트 초기화
    function resetHighlight() {
        nodeCircles.style('opacity', 1);
        nodeLabels.style('opacity', 1);
        link.style('opacity', 0.6);
    }

    // 툴팁 기능
    const tooltip = d3.select('body')
        .append('div')
        .attr('class', 'graph-tooltip')
        .style('position', 'absolute')
        .style('visibility', 'hidden')
        .style('background', 'rgba(0, 0, 0, 0.8)')
        .style('color', 'white')
        .style('padding', '8px 12px')
        .style('border-radius', '4px')
        .style('font-size', '12px')
        .style('pointer-events', 'none')
        .style('z-index', '1000');

    function showTooltip(event, d) {
        const dependencyCount = (data.functions && data.functions[d.id] && data.functions[d.id].dependencies) ? 
            data.functions[d.id].dependencies.length : 0;
        
        tooltip
            .html(`
                <strong>${d.name}</strong><br/>
                <span style="color: #ccc;">${d.description}</span><br/>
                <small>의존성: ${dependencyCount}개</small>
            `)
            .style('visibility', 'visible')
            .style('left', (event.pageX + 10) + 'px')
            .style('top', (event.pageY - 10) + 'px');
    }

    function hideTooltip() {
        tooltip.style('visibility', 'hidden');
    }

    // 컨트롤 버튼 이벤트
    d3.select('#reset-graph').on('click', function() {
        resetHighlight();
        const transform = d3.zoomIdentity;
        svg.transition().duration(750).call(zoom.transform, transform);
    });

    d3.select('#fit-graph').on('click', function() {
        const bounds = g.node().getBBox();
        const parent = svg.node().getBoundingClientRect();
        const fullWidth = parent.width;
        const fullHeight = parent.height;
        const widthScale = fullWidth / bounds.width;
        const heightScale = fullHeight / bounds.height;
        const scale = Math.min(widthScale, heightScale) * 0.9;
        const translate = [
            (fullWidth - scale * (bounds.x + bounds.x + bounds.width)) / 2,
            (fullHeight - scale * (bounds.y + bounds.y + bounds.height)) / 2
        ];
        
        const transform = d3.zoomIdentity
            .translate(translate[0], translate[1])
            .scale(scale);
        
        svg.transition().duration(750).call(zoom.transform, transform);
    });

    d3.select('#zoom-in').on('click', function() {
        svg.transition().duration(300).call(zoom.scaleBy, 1.5);
    });

    d3.select('#zoom-out').on('click', function() {
        svg.transition().duration(300).call(zoom.scaleBy, 1 / 1.5);
    });

    // 배경 클릭시 하이라이트 초기화
    svg.on('click', function(event) {
        if (event.target === this) {
            resetHighlight();
        }
    });

    // Public API 반환
    return {
        selectFunction: function(functionId) {
            highlightNode(functionId);
        },
        
        resetHighlight: function() {
            resetHighlight();
        },
        
        setViewMode: function(mode) {
            console.log('View mode changed to:', mode);
            // 향후 구현: 리스트 뷰 등
        },
        
        applyFilters: function(filters) {
            const { search, category } = filters;
            
            nodeCircles.style('opacity', d => {
                const matchesSearch = !search || d.name.toLowerCase().includes(search.toLowerCase());
                const matchesCategory = !category || d.category === category;
                return matchesSearch && matchesCategory ? 1 : 0.2;
            });
            
            nodeLabels.style('opacity', d => {
                const matchesSearch = !search || d.name.toLowerCase().includes(search.toLowerCase());
                const matchesCategory = !category || d.category === category;
                return matchesSearch && matchesCategory ? 1 : 0.2;
            });
        }
    };
}