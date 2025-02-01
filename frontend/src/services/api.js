import axios from 'axios';

const API_BASE_URL = 'http://127.0.0.1:8000/api';

export const getClients = () => axios.get(`${API_BASE_URL}/clients`);
export const getCars = (clientId) => axios.get(`${API_BASE_URL}/clients/${clientId}/get-cars`);
export const getServices = (clientId, carId) => axios.get(`${API_BASE_URL}/services/${clientId}/${carId}/get-service`);

export const getClientByName = async (name) => {
  try {
    const response = await getClients();
    const filteredClients = response.data.filter(client => client.name.toLowerCase().includes(name.toLowerCase()));
    return { data: filteredClients };
  } catch (error) {
    console.error('Error fetching client by name:', error);
    throw error;
  }
};

export const getClientByDocumentId = async (documentId) => {
  try {
    const response = await getClients();
    const filteredClients = response.data.filter(client => client.card_number.toString().includes(documentId));
    return { data: filteredClients };
  } catch (error) {
    console.error('Error fetching client by document ID:', error);
    throw error;
  }
};