import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'leaflet/dist/leaflet.css';
import './styles/app.css';

import L from 'leaflet';

var map = L.map('map').setView([48.8566, 2.3522], 13); // Coordonnées de Paris (à ajuster)

// Ajouter la couche OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

// Ajouter un marqueur pour le théâtre
var marker = L.marker([48.8566, 2.3522]).addTo(map); // Coordonnées du théâtre

// Ajouter une infobulle avec des informations sur le théâtre
marker.bindPopup("<b>Théâtre de Paris</b><br>12 Rue de la Paix, 75002 Paris<br><a href='https://www.openstreetmap.org/directions?from=&to=48.8566,2.3522#map=16/48.8566/2.3522' target='_blank'>Obtenir l'itinéraire</a>").openPopup();

