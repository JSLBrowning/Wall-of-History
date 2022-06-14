const scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });

const controls = new THREE.OrbitControls(camera, renderer.domElement);
const loadManager = new THREE.LoadingManager();
const loader = new THREE.TextureLoader(loadManager);

renderer.setSize(window.innerWidth, window.innerHeight);
renderer.setClearColor(0x000000, 0.75);

document.body.appendChild(renderer.domElement);

const geometry = new THREE.BoxGeometry(1, 1.469, 0.0625);
const material = new THREE.MeshBasicMaterial({ color: 0x0000ff });
const bookMaterial = new THREE.MeshBasicMaterial({ map: loader.load('/img/story/contents/GNO2P6.png') });
const materials = [
    new THREE.MeshBasicMaterial({ color: 0xfdf5e8 }),
    new THREE.MeshBasicMaterial({ map: loader.load('/img/test/TotTSide.jpg') }),
    new THREE.MeshBasicMaterial({ color: 0xfdf5e8 }),
    new THREE.MeshBasicMaterial({ color: 0xfdf5e8 }),
    new THREE.MeshBasicMaterial({ map: loader.load('/img/story/contents/GNO2P6.png') }),
    new THREE.MeshBasicMaterial({ map: loader.load('/img/test/TotTBack.jpg') }),
];
const book = new THREE.Mesh(geometry, materials);
scene.add(book);

camera.position.z = 1.5;


function animate() {
    requestAnimationFrame(animate);
    controls.update();
    renderer.render(scene, camera);
}


animate();