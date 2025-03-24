import './bootstrap.js';
import '../css/app.css';
import React from 'react';
import { createRoot } from 'react-dom/client'; // Use createRoot for React 18
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Home from './components/Home.jsx';
import Dash from './components/Dash.jsx';
import Products from './components/Products.jsx';
import Header from './components/Header.jsx';
import AuthForm from './components/AuthForm.jsx';

const App = () => {
  return (
    <Router>
      <Header /> 
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/Dash" element={<Dash />} />
        <Route path="/products" element={<Products />} />

        <Route path="/login" element={<AuthForm />} />
      </Routes>
    </Router>
  );
};

// Use createRoot for React 18
const container = document.getElementById('app');
const root = createRoot(container);
root.render(
  <React.StrictMode>
    <App />
  </React.StrictMode>
);