import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import '../../css/Dash.css';
import { Link } from 'react-router-dom';


const MyTickets = ({ clientId  ,token}) => {
  const [tickets, setTickets] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const back = {
    backgroundColor: '#4CAF50',
    color: 'white',
    padding: '10px 20px',
    border: 'none',
    borderRadius: '5px',
    cursor: 'pointer',
  };

  useEffect(() => {
    const fetchTickets = async () => {
      try {
        console.log( `Bearer ${token}`);
        
        const response = await fetch(`http://127.0.0.1:8000/api/tickets/client/${clientId}`, {
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
              <td>{ticket.agent_id!=null ? ticket.agent_name : 'empty'}</td>
              <td>{ticket.title}</td>
              <td>
                <span className={`status-badge ${ticket.status.toLowerCase().replace(' ', '-')}`}>
                  {ticket.status}
                </span>
              </td>
              <td>{ticket.progress}</td>
              <td>
                 <Link className='status' style={back} to={`/details/${ticket.id}`} key={ticket.id}> View  </Link>
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
  
  // useEffect(() => {
  //   if (!localStorage.getItem('authToken')) {
  //     navigate('/login');
  //   }
  //   setTickets([
  //     { id: 1, title: 'Login issue', description: 'Cannot log in', status: 'Open' },
  //     { id: 2, title: 'Dashboard bug', description: 'Stats not showing', status: 'In Progress' }
  //   ]);
  // }, [navigate]);

  // const handleLogout = () => {
  //   localStorage.clear();
  //   navigate('/login');
  // };

  const Dashboard = () => (
    <div className="dashboard-content">
      <h2>Dashboard Overview</h2>
      <div className="stats">
        <div className="stat-card">
          <h3>Open Tickets</h3>
          <p>{tickets.filter(t => t.status === 'Open').length}</p>
        </div>
        <div className="stat-card">
          <h3>In Progress</h3>
          <p>{tickets.filter(t => t.status === 'In Progress').length}</p>
        </div>
        <div className="stat-card">
          <h3>Total Tickets</h3>
          <p>{tickets.length}</p>
        </div>
      </div>
    </div>
  );

  const AddTicketModal = () => (
    <div className="modal-overlay">
      <div className="modal">
        <div className="modal-header">
          <h2>Add New Ticket</h2>
          <button onClick={() => setShowAddTicket(false)} className="close-btn">
            &times;
          </button>
        </div>
        <form onSubmit={handleAddTicket}>
          <div className="form-group">
            <label>Title</label>
            <input type="text" name="title" required />
          </div>
          <div className="form-group">
            <label>Description</label>
            <textarea name="description" required></textarea>
          </div>
          <div className="form-actions">
            <button type="button" onClick={() => setShowAddTicket(false)}>
              Cancel
            </button>
            <button type="submit">Submit</button>
          </div>
        </form>
      </div>
    </div>
  );

  return (
    <div className="dashboard-layout">
      <div className="sidebar">
        <div className="sidebar-header">
          <h3>Ticket System</h3>
        </div>
        <ul className="sidebar-menu">
          <li
            className={activeTab === 'myTickets' ? 'active' : ''}
            onClick={() => setActiveTab('myTickets')}
          >
            My Tickets {userData.name +' new' + userData.name }
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
            {/* <button onClick={handleLogout} className="logout-button">
              Logout
            </button> */}
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