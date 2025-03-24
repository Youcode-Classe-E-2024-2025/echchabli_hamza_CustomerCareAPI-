import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';

const AuthForm = () => {
  const navigate = useNavigate();
  const [activeTab, setActiveTab] = useState('login');
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');

  const [loginForm, setLoginForm] = useState({
    email: '',
    password: ''
  });

  const [registerForm, setRegisterForm] = useState({
    name: '',
    email: '',
    password: '',
    confirmPassword: '',
    role: 'client'
  });

  const handleInputChange = (e, formSetter) => {
    const { name, value } = e.target;
    formSetter(prev => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (e, formData, endpoint, successMessage) => {
    e.preventDefault();
    setError('');
    setSuccess('');

    if (endpoint === '/api/register' && formData.password !== formData.confirmPassword) {
      setError('Passwords do not match');
      return;
    }

    try {
      const response = await fetch(endpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData)
      });

      const data = await response.json();

      if (!response.ok) throw new Error(data.message || 'Request failed');

      setSuccess(successMessage);
      localStorage.setItem('authToken', data.token);

      if (endpoint === '/api/register') setActiveTab('login');

      console.log(data.user);
      

      localStorage.setItem('authToken', data.token);
     localStorage.setItem('userId', data.user.id);      
    localStorage.setItem('userRole', data.role);
    localStorage.setItem('name', data.user.name);
    localStorage.setItem('email', data.user.email);
      navigate('/Dash'); 
    } catch (error) {
      setError(error.message || 'An error occurred');
    }
  };

  return (
    <div className="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl">
      <div className="p-8">
        {success && <div className="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">{success}</div>}
        {error && <div className="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">{error}</div>}

        <div className="flex border-b mb-4">
          {['login', 'register'].map(tab => (
            <button
              key={tab}
              className={`py-2 px-4 ${activeTab === tab ? 'border-b-2 border-blue-500 text-blue-500' : 'text-gray-500'}`}
              onClick={() => setActiveTab(tab)}
            >
              {tab.charAt(0).toUpperCase() + tab.slice(1)}
            </button>
          ))}
        </div>

        {activeTab === 'login' ? (
          <form onSubmit={(e) => handleSubmit(e, loginForm, '/api/login', 'Logged in successfully!')} className="space-y-4">
            <input
              type="email"
              name="email"
              placeholder="Email"
              value={loginForm.email}
              onChange={(e) => handleInputChange(e, setLoginForm)}
              required
              className="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <input
              type="password"
              name="password"
              placeholder="Password"
              value={loginForm.password}
              onChange={(e) => handleInputChange(e, setLoginForm)}
              required
              className="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <button type="submit" className="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">Login</button>
          </form>
        ) : (
          <form onSubmit={(e) => handleSubmit(e, registerForm, '/api/register', 'Registered successfully!')} className="space-y-4">
            <input
              type="text"
              name="name"
              placeholder="Full Name"
              value={registerForm.name}
              onChange={(e) => handleInputChange(e, setRegisterForm)}
              required
              className="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <input
              type="email"
              name="email"
              placeholder="Email"
              value={registerForm.email}
              onChange={(e) => handleInputChange(e, setRegisterForm)}
              required
              className="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <input
              type="password"
              name="password"
              placeholder="Password"
              value={registerForm.password}
              onChange={(e) => handleInputChange(e, setRegisterForm)}
              required
              className="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <input
              type="password"
              name="confirmPassword"
              placeholder="Confirm Password"
              value={registerForm.confirmPassword}
              onChange={(e) => handleInputChange(e, setRegisterForm)}
              required
              className="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <select
              name="role"
              value={registerForm.role}
              onChange={(e) => handleInputChange(e, setRegisterForm)}
              className="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="client">Client</option>
              <option value="agent">Agent</option>
            </select>
            <button type="submit" className="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">Register</button>
          </form>
        )}
      </div>
    </div>
  );
};

export default AuthForm;
