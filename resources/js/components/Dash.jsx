import React, { useEffect } from 'react';
import { useNavigate } from 'react-router-dom';

const Dash = () => {
  const navigate = useNavigate();
  
  // Get user data from localStorage
  const userData = {
    name: localStorage.getItem('name'),
    email: localStorage.getItem('email'),
    role: localStorage.getItem('userRole'),
    id: localStorage.getItem('userId'),
    token: localStorage.getItem('authToken')?.substring(0, 15) + '...' // Only show first 15 chars
  };

  // Check authentication on component mount
  useEffect(() => {
    if (!localStorage.getItem('authToken')) {
      navigate('/login'); // Redirect to login if not authenticated
    }
  }, [navigate]);

  // Handle logout
  const handleLogout = () => {
    // Clear all user-related data
    localStorage.removeItem('authToken');
    localStorage.removeItem('name');
    localStorage.removeItem('email');
    localStorage.removeItem('userRole');
    localStorage.removeItem('userId');
    navigate('/login');
  };

  // Show loading state while checking auth
  if (!userData.token) {
    return <div>Loading...</div>;
  }

  return (
    <div className="dashboard-container">
      <header className="dashboard-header">
        <h1>Welcome to Your Dashboard</h1>
        <button onClick={handleLogout} className="logout-button">
          Logout
        </button>
      </header>

      <div className="user-profile">
        <h2>Your Profile</h2>
        <div className="profile-detail">
          <span className="detail-label">Name:</span>
          <span>{userData.name || 'Not provided'}</span>
        </div>
        <div className="profile-detail">
          <span className="detail-label">Email:</span>
          <span>{userData.email}</span>
        </div>
        <div className="profile-detail">
          <span className="detail-label">Role:</span>
          <span className={`role-badge ${userData.role}`}>
            {userData.role}
          </span>
        </div>
        <div className="profile-detail">
          <span className="detail-label">User ID:</span>
          <span>{userData.id}</span>
        </div>
      </div>

      <div className="security-note">
        <p>For security reasons, only a portion of your token is displayed.</p>
        <p>Token: {userData.token}</p>
      </div>
    </div>
  );
};

export default Dash;