/**
 * COMPASS 3D Animated Block Canvas Renderer (Resend-Style Assembly)
 * Powered by Three.js
 */

(function () {
  'use strict';

  function initCompass3DBlock(wrapper) {
    if (!window.THREE || wrapper.dataset.initialized === 'true') return;
    wrapper.dataset.initialized = 'true';

    const canvas = wrapper.querySelector('.compass-3d-block-canvas');
    if (!canvas) return;

    // Config options
    const theme = wrapper.dataset.theme || 'dark';
    const gridSize = parseInt(wrapper.dataset.gridSize || '3', 10);
    const accentHex = wrapper.dataset.accentColor || 'rgb(61, 238, 152)';
    const isInteractive = wrapper.dataset.interactive !== 'false';

    // Scene Setup
    const scene = new THREE.Scene();

    // Camera
    const width = wrapper.clientWidth || 500;
    const height = wrapper.clientHeight || 450;
    const aspect = width / height;

    const camera = new THREE.PerspectiveCamera(38, aspect, 0.1, 1000);
    camera.position.set(12, 10, 14);
    camera.lookAt(0, 0, 0);

    // Renderer
    const renderer = new THREE.WebGLRenderer({
      canvas: canvas,
      alpha: true,
      antialias: true,
      powerPreference: 'high-performance',
    });
    renderer.setSize(width, height);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

    // Lighting
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.3);
    scene.add(ambientLight);

    const dirLight1 = new THREE.DirectionalLight(0xffffff, 1.5);
    dirLight1.position.set(15, 20, 15);
    scene.add(dirLight1);

    const dirLight2 = new THREE.DirectionalLight(0x1a2233, 0.8);
    dirLight2.position.set(-15, -10, -15);
    scene.add(dirLight2);

    const accentPointLight = new THREE.PointLight(new THREE.Color(accentHex), 4.0, 30);
    accentPointLight.position.set(0, 0, 0);
    scene.add(accentPointLight);

    // Materials - Pure Jet Black Box
    const baseColor = theme === 'neon' ? 0x03070b : (theme === 'glass' ? 0x090a0f : 0x020203);
    const cubeMaterial = new THREE.MeshStandardMaterial({
      color: baseColor,
      roughness: 0.15,
      metalness: 0.95,
    });

    const edgeMaterial = new THREE.LineBasicMaterial({
      color: new THREE.Color(accentHex),
      transparent: true,
      opacity: 0.85,
    });

    // Parent Group
    const blockGroup = new THREE.Group();
    scene.add(blockGroup);

    // Create Matrix Sub-Cubes
    const cubeSize = 1.6;
    const gap = 0.22;
    const offset = ((gridSize - 1) * (cubeSize + gap)) / 2;

    const subCubes = [];

    for (let x = 0; x < gridSize; x++) {
      for (let y = 0; y < gridSize; y++) {
        for (let z = 0; z < gridSize; z++) {
          const subGroup = new THREE.Group();

          // Geometry
          const geom = new THREE.BoxGeometry(cubeSize, cubeSize, cubeSize);
          const mesh = new THREE.Mesh(geom, cubeMaterial);
          subGroup.add(mesh);

          // Wireframe edges
          const edges = new THREE.EdgesGeometry(geom);
          const line = new THREE.LineSegments(edges, edgeMaterial);
          subGroup.add(line);

          // Position targets
          const homeX = x * (cubeSize + gap) - offset;
          const homeY = y * (cubeSize + gap) - offset;
          const homeZ = z * (cubeSize + gap) - offset;

          subGroup.position.set(homeX, homeY, homeZ);

          subCubes.push({
            group: subGroup,
            homeX: homeX,
            homeY: homeY,
            homeZ: homeZ,
            phase: (x + y + z) * 0.4,
            explodeMult: Math.random() * 0.8 + 0.4,
          });

          blockGroup.add(subGroup);
        }
      }
    }

    // Floating Background Particles
    const particleCount = 40;
    const particleGeom = new THREE.BufferGeometry();
    const particlePositions = new Float32Array(particleCount * 3);

    for (let i = 0; i < particleCount * 3; i += 3) {
      particlePositions[i] = (Math.random() - 0.5) * 30;
      particlePositions[i + 1] = (Math.random() - 0.5) * 30;
      particlePositions[i + 2] = (Math.random() - 0.5) * 30;
    }

    particleGeom.setAttribute('position', new THREE.BufferAttribute(particlePositions, 3));
    const particleMat = new THREE.PointsMaterial({
      color: new THREE.Color(accentHex),
      size: 0.12,
      transparent: true,
      opacity: 0.4,
    });

    const particles = new THREE.Points(particleGeom, particleMat);
    scene.add(particles);

    // Mouse Interaction
    let targetRotationX = 0;
    let targetRotationY = 0;
    let currentRotationX = 0;
    let currentRotationY = 0;

    if (isInteractive) {
      wrapper.addEventListener('mousemove', function (e) {
        const rect = wrapper.getBoundingClientRect();
        const mouseX = ((e.clientX - rect.left) / rect.width) * 2 - 1;
        const mouseY = -(((e.clientY - rect.top) / rect.height) * 2 - 1);

        targetRotationY = mouseX * 0.5;
        targetRotationX = -mouseY * 0.5;
      });

      wrapper.addEventListener('mouseleave', function () {
        targetRotationX = 0;
        targetRotationY = 0;
      });
    }

    // Animation Cycle
    let clock = new THREE.Clock();

    function animate() {
      requestAnimationFrame(animate);

      const elapsedTime = clock.getElapsedTime();

      // Continuous subtle block group rotation + Mouse Parallax
      currentRotationX += (targetRotationX - currentRotationX) * 0.05;
      currentRotationY += (targetRotationY - currentRotationY) * 0.05;

      blockGroup.rotation.y = elapsedTime * 0.25 + currentRotationY;
      blockGroup.rotation.x = Math.sin(elapsedTime * 0.15) * 0.15 + currentRotationX;
      particles.rotation.y = elapsedTime * 0.05;

      // Resend-style sub-cube assembly expansion pulse cycle
      const assemblyCycle = (Math.sin(elapsedTime * 1.2) + 1) / 2; // 0 to 1
      const expandFactor = Math.pow(assemblyCycle, 2.5) * 0.85;

      subCubes.forEach((cube) => {
        const localPhase = Math.sin(elapsedTime * 1.5 + cube.phase) * expandFactor;
        const currentExpand = Math.max(0, localPhase);

        cube.group.position.x = cube.homeX + (cube.homeX * currentExpand * 0.4);
        cube.group.position.y = cube.homeY + (cube.homeY * currentExpand * 0.4);
        cube.group.position.z = cube.homeZ + (cube.homeZ * currentExpand * 0.4);

        cube.group.rotation.x = currentExpand * 0.2;
        cube.group.rotation.y = currentExpand * 0.2;
      });

      renderer.render(scene, camera);
    }

    animate();

    // Responsive Canvas Resize
    const resizeObserver = new ResizeObserver(() => {
      const newWidth = wrapper.clientWidth || 500;
      const newHeight = wrapper.clientHeight || 450;

      camera.aspect = newWidth / newHeight;
      camera.updateProjectionMatrix();

      renderer.setSize(newWidth, newHeight);
    });

    resizeObserver.observe(wrapper);
  }

  function initAll() {
    const wrappers = document.querySelectorAll('.compass-3d-block-wrapper');
    wrappers.forEach(initCompass3DBlock);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAll);
  } else {
    initAll();
  }
})();
