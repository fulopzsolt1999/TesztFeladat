import axios from 'axios';

export const getClients = () => axios.get('http://127.0.0.1:8000/api/clients');
export const getCars = (clientId) => axios.get(`http://127.0.0.1:8000/api/clients/${clientId}/get-cars`);
export const getServices = (clientId, carId) => axios.get(`http://127.0.0.1:8000/api/services/${clientId}/${carId}/get-service`);