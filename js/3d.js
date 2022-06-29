let scene, camera, renderer, controls, book


function init3D(width, height, depth, id) {
    // Fetch container.
    let container = document.getElementsByClassName("zoom")[0];

    // Create and set up basic elements.
    scene = new THREE.Scene();
    camera = new THREE.PerspectiveCamera(75, (.875 * window.innerWidth) / (.875 * window.innerHeight), 0.1, 1000);
    camera.position.z = 1.375;
    renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
    renderer.setSize(.875 * window.innerWidth, .875 * window.innerHeight);
    renderer.setClearColor(0x000000, 0);

    // Set up controls.
    controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.autoRotate = true;
    controls.addEventListener('start', function () {
        controls.autoRotate = false;
    });

    // Load textures and create object.
    loadManager = new THREE.LoadingManager();
    loader = new THREE.TextureLoader(loadManager);
    const geometry = new THREE.BoxGeometry(width, height, depth);
    const materials = [
        new THREE.MeshBasicMaterial({ map: loader.load('/img/3d/' + id + '/1.webp') }),
        new THREE.MeshBasicMaterial({ map: loader.load('/img/3d/' + id + '/2.webp') }),
        new THREE.MeshBasicMaterial({ map: loader.load('/img/3d/' + id + '/3.webp') }),
        new THREE.MeshBasicMaterial({ map: loader.load('/img/3d/' + id + '/4.webp') }),
        new THREE.MeshBasicMaterial({ map: loader.load('/img/3d/' + id + '/5.webp') }),
        new THREE.MeshBasicMaterial({ map: loader.load('/img/3d/' + id + '/6.webp') }),
    ];
    const book = new THREE.Mesh(geometry, materials);

    // Add elements to container and start animation.
    container.appendChild(renderer.domElement);
    scene.add(book);
    window.addEventListener('resize', onWindowResize, false);
    animate();
}


function animate() {
    requestAnimationFrame(animate);
    controls.update();
    renderer.render(scene, camera);
}


function onWindowResize() {
    camera.aspect = (.875 * window.innerWidth) / (.875 * window.innerHeight);
    camera.updateProjectionMatrix();
    renderer.setSize(.875 * window.innerWidth, .875 * window.innerHeight);
}

