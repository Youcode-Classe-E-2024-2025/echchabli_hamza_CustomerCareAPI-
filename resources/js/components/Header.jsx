import React from 'react';
import { Link, useLocation } from 'react-router-dom';
import { useNavigate } from 'react-router-dom';


const Header = () => {
    const navigate = useNavigate();
  
  const location = useLocation();

  const isLoggedIn = !!localStorage.getItem('authToken');

  const handleLogout = async () => {
    try {
      const token = localStorage.getItem('authToken');
  
      const response = await fetch('/api/logout', {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
        },
      });
  
      if (response.ok) {
        localStorage.removeItem('authToken');
        
        navigate('/login');
      } else {
        console.error('Logout failed');
      }
    } catch (error) {
      console.error('Error during logout:', error);
    }
  };
  
  const isActive = (path) => {
    return location.pathname === path;
  };

  return (
    <header className="bg-gray-800 shadow-md">
      <div className="container mx-auto px-4">
        <nav className="flex items-center justify-between py-4">
          <div className="text-white font-bold text-xl">
            <Link to="/">MyApp</Link>
          </div>
          
          <ul className="flex space-x-6">
            <li>
              <Link 
                to="/" 
                className={`${isActive('/') 
                  ? 'text-white font-medium border-b-2 border-blue-500' 
                  : 'text-gray-300 hover:text-white'} 
                  transition duration-200`}
              >
                Home
              </Link>
            </li>
            <li>
  {(() => {
    const userRole = localStorage.getItem('userRole');
    console.log(userRole);
    
    let dashboardLink = null;

    if (userRole === "client") {
      dashboardLink = "/Dash";
    } else if (userRole === "agent") {
      dashboardLink = "/agentDash";
    } else if (userRole === "admin") {
      // console.log('here');
      
      dashboardLink = "/adminDash";
      
    }

    console.log(dashboardLink);
    

    return dashboardLink ? (
      <Link 
        to={dashboardLink} 
        
        className={`${isActive(dashboardLink) 
          ? 'text-white font-medium border-b-2 border-blue-500' 
          : 'text-gray-300 hover:text-white'} 
          transition duration-200`}
      >
        Dashboard
      </Link>
    ) : null;
  })()}
</li>

          
                  
          </ul>
          
          <div className="hidden md:block">
          {isLoggedIn ? (
  <button
    onClick={handleLogout}
    className="logout-button bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200">
    Logout
  </button>
) : (
  <Link
    to="/login"
    className="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200">
    Login
  </Link>
)}
          </div>
        </nav>
      </div>
    </header>
  );
};

export default Header;