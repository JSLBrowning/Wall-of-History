let scene, camera, renderer, controls, book


function init3D(width, height, depth, id) {
    let container = document.getElementsByClassName("zoom")[0];

    scene = new THREE.Scene();
    camera = new THREE.PerspectiveCamera(75, (.875 * window.innerWidth) / (.875 * window.innerHeight), 0.1, 1000);
    renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });

    controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.autoRotate = true;
    controls.addEventListener('start', function () {
        controls.autoRotate = false;
    });

    loadManager = new THREE.LoadingManager();
    loader = new THREE.TextureLoader(loadManager);

    renderer.setSize(.875 * window.innerWidth, .875 * window.innerHeight);
    renderer.setClearColor(0x000000, 0);

    container.appendChild(renderer.domElement);

    const geometry = new THREE.BoxGeometry(width, height, depth);
    const materials = [
        new THREE.MeshBasicMaterial({ map: loader.load('/img/3d/' + id + '/1.jpg') }),
        new THREE.MeshBasicMaterial({ map: loader.load('/img/3d/' + id + '/2.jpg') }),
        new THREE.MeshBasicMaterial({ map: loader.load('/img/3d/' + id + '/3.jpg') }),
        new THREE.MeshBasicMaterial({ map: loader.load('/img/3d/' + id + '/4.jpg') }),
        new THREE.MeshBasicMaterial({ map: loader.load('/img/3d/' + id + '/5.webp') }),
        new THREE.MeshBasicMaterial({ map: loader.load('/img/3d/' + id + '/6.jpg') }),
    ];
    const book = new THREE.Mesh(geometry, materials);
    scene.add(book);

    camera.position.z = 1.375;

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

