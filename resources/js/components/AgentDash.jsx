import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import '../../css/Dash.css';

const MyTickets = ({ clientId  ,token}) => {
  const [tickets, setTickets] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchTickets = async () => {
      try {
        console.log( `Bearer ${token}`);
        
        const response = await fetch(`http://127.0.0.1:8000/api/tickets/agent/${clientId}`, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
          },
        });
        
        if (!response.ok) {
          throw new Error('Failed to fetch tickets');
        }

        const data = await response.json();
        console.log(data.tickets);
        
        setTickets(data.tickets);
        setLoading(false);
      } catch (err) {
        setError(err.message);
        setLoading(false);
      }
    };

    fetchTickets();
  }, [clientId]);

  const handleViewTicket = (ticketId) => {
    console.log(`View ticket with ID: ${ticketId}`);
  };

  const handleCancelTicket = (ticketId) => {
    console.log(`Cancel ticket with ID: ${ticketId}`);
  };

  if (loading) return <p>Loading...</p>;
  if (error) return <p>{error}</p>;

  return (
    <div className="tickets-container">
      <table className="tickets-table">
        <thead>
          <tr>
            <th>Agent Name</th>
            <th>Title</th>
            <th>Status</th>
            <th>Progress</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          {tickets.map((ticket) => (
            <tr key={ticket.id}>
              <td>{ticket.agent_name!=null ? ticket.agent_name : 'empty'}</td>
              <td>{ticket.title}</td>
              <td>
                <span className={`status-badge ${ticket.status.toLowerCase().replace(' ', '-')}`}>
                  {ticket.status}
                </span>
              </td>
              <td>{ticket.progress}</td>
              <td>
                <button onClick={() => handleViewTicket(ticket.id)} className="view-ticket-btn">
                  View
                </button>
                <button onClick={() => handleCancelTicket(ticket.id)} className="cancel-ticket-btn">
                  Cancel
                </button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

const Dash = () => {
  const navigate = useNavigate();
  const [activeTab, setActiveTab] = useState('myTickets');
  const [tickets, setTickets] = useState([]);
  const [showAddTicket, setShowAddTicket] = useState(false);

  const userData = {
    name: localStorage.getItem('name'),
    email: localStorage.getItem('email'),
    role: localStorage.getItem('userRole'),
    id: localStorage.getItem('userId'),
    token: localStorage.getItem('authToken')
  };

  const handleAddTicket = async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    
    const newTicket = {
      title: formData.get('title'),
      description: formData.get('description'),
      owner_id: localStorage.getItem('userId'),
    };

    try {
      const response = await fetch('/api/tickets', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${userData.token}`,
        },
        body: JSON.stringify(newTicket),
      });
   
      if (!response.ok) throw new Error('Failed to add ticket');
      
      const data = await response.json();
      console.log('Ticket added:', data);
      e.target.reset();
      setShowAddTicket(false);
    } catch (error) {
      console.error('Error adding ticket:', error);
    }
  };
  


  const handleLogout = () => {
    localStorage.clear();
    navigate('/login');
  };




  return (
    <div className="dashboard-layout">
      <div className="sidebar">
        <div className="sidebar-header">
          <h3>agent</h3>
        </div>
        <ul className="sidebar-menu">
          <li
            className={activeTab === 'myTickets' ? 'active' : ''}
            onClick={() => setActiveTab('myTickets')}
          >
            LLLLLLLL {userData.role +' new' + userData.name }
          </li>
          <li
            className={activeTab === 'dashboard' ? 'active' : ''}
            onClick={() => setActiveTab('dashboard')}
          >
            Dashboard
          </li>
        </ul>
        <div className="user-info">
          <span>{userData.name}</span>
          <span className="user-role">{userData.role}</span>
        </div>
      </div>

      <div className="main-content">
        <header className="dashboard-header">
          <h1>
            {activeTab === 'myTickets' ? 'My Tickets' : 'Dashboard'}
          </h1>
          <div className="header-actions">
            {activeTab === 'myTickets' && (
              <button 
                onClick={() => setShowAddTicket(true)} 
                className="add-ticket-btn"
              >
                Add Ticket
              </button>
            )}
            <button onClick={handleLogout} className="logout-button">
              Logout
            </button>
          </div>
        </header>

        {activeTab === 'myTickets' ? (
          <MyTickets clientId={userData.id} token={userData.token} />
        ) : (
          <Dashboard />
        )}
        {showAddTicket && <AddTicketModal />}
      </div>
    </div>
  );
};

export default Dash;