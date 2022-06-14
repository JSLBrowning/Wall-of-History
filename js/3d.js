const scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
const renderer = new THREE.WebGLRenderer();

const controls = new THREE.OrbitControls(camera, renderer.domElement);

const loadManager = new THREE.LoadingManager();
const loader = new THREE.TextureLoader(loadManager);

renderer.setSize(window.innerWidth, window.innerHeight);

document.body.appendChild(renderer.domElement);



const geometry = new THREE.BoxGeometry(1, 1.469, 0.0625);
const material = new THREE.MeshBasicMaterial({ color: 0x0000ff });
const bookMaterial = new THREE.MeshBasicMaterial({ map: loader.load('/img/story/contents/GNO2P6.png') });
const materials = [
    new THREE.MeshBasicMaterial({ color: 0xF0EAD6 }),
    new THREE.MeshBasicMaterial({ map: loader.load('/img/test/TotTSide.jpg') }),
    new THREE.MeshBasicMaterial({ color: 0xF0EAD6 }),
    new THREE.MeshBasicMaterial({ color: 0xF0EAD6 }),
    new THREE.MeshBasicMaterial({ map: loader.load('/img/story/contents/GNO2P6.png') }),
    new THREE.MeshBasicMaterial({ map: loader.load('/img/test/TotTBack.jpg') }),
];
const cube = new THREE.Mesh(geometry, materials);
scene.add(cube);

camera.position.z = 2;


function animate() {
    requestAnimationFrame(animate);

    // required if controls.enableDamping or controls.autoRotate are set to true
    controls.update();

    renderer.render(scene, camera);
}


animate();