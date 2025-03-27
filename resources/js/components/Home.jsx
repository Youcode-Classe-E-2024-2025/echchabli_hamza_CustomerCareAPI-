import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import '../../css/home.css';

const Home = () => {
  const [tickets, setTickets] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const [filters, setFilters] = useState({
    status: 'open',
    sort_direction: 'desc'
  });

  const [pagination, setPagination] = useState({
    currentPage: 1,
    perPage: 2,
    total: 0,
    lastPage: 0
  });


  const fetchOpenTickets = async () => {
    try {
      const response = await fetch('/api/tickets/open', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          status: filters.status,
          sort_direction: filters.sort_direction,
          per_page: pagination.perPage,
          page: pagination.currentPage
        })
      });

      const data = await response.json();
      console.log(data);
      
      
      if (!response.ok) throw new Error(data.message || 'Failed to fetch tickets');
      
      setTickets(data.tickets.data || []);
      setPagination(prev => ({
            ...prev,
        currentPage:data.tickets.current_page,
        perPage : data.tickets.per_page ,
        total: data.tickets.total,
        lastPage: data.tickets.last_page
      }));
      setLoading(false);
    } catch (err) {
      setError(err.message);
      setLoading(false);
    }
  };
  const handleAssignTicket = async () => {
    setAssigning(true); // Show loading state for the button

    try {
      // Call the API to assign an agent to the ticket
      const response = await fetch(`/tickets/${id}/assign`, {
        method: 'PUT', // Assuming PUT method to update ticket assignment
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ agent_id: 1 }), // Assuming agent ID is 1 for example
      });
      const data = await response.json();

      if (response.ok) {
        setTicket(data.ticket); // Update ticket data with the new agent_id
      } else {
        setError(data.message);
      }
    } catch (err) {
      setError('Error assigning the ticket');
    } finally {
      setAssigning(false); // Hide loading state
    }
  };

  useEffect(() => {
    fetchOpenTickets();
  }, [filters, pagination.currentPage]);

  const handleStatusChange = (status) => {
    setFilters(prev => ({ ...prev, status }));
    setPagination(prev => ({ ...prev, currentPage: 1 }));
  };

  const handleSortChange = (sort_direction) => {
    setFilters(prev => ({ ...prev, sort_direction }));
    setPagination(prev => ({ ...prev, currentPage: 1 }));
  };

  const handlePageChange = (page) => {
    setPagination(prev => ({ ...prev, currentPage: page }));
  };

  return (
    <div className="tickets-page">
      <div className="filters">
        <select 
          value={filters.status} 
          onChange={(e) => handleStatusChange(e.target.value)}
        >
          <option value="open">Open</option>
          <option value="closed">Closed</option>
        </select>

        <select 
          value={filters.sort_direction}
          onChange={(e) => handleSortChange(e.target.value)}
        >
          <option value="desc">Newest First</option>
          <option value="asc">Oldest First</option>
        </select>
      </div>

      {loading ? (
        <div className="loading">Loading tickets...</div>
      ) : error ? (
        <div className="error">Error: {error}</div>
      ) : (
        <div className="tickets-list">
          {tickets.length === 0 ? (
            <div className="no-tickets">No tickets found</div>
          ) : (
            <>
              <table>
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>check</th>
                  </tr>
                </thead>
                <tbody>
                  {tickets.map(ticket => (
                    <tr key={ticket.id}>
                      <td>{ticket.title || 'N/A'}</td>
                      <td>{ticket.description || 'N/A'}</td>
                      <td>
                        <span className={`status ${ticket.status}`}>
                          {ticket.status}
                        </span>
                      </td>
                      <td>{new Date(ticket.created_at).toLocaleDateString()}</td>

                      <td><Link to={`/details/${ticket.id}`} key={ticket.id}> View  </Link></td>

                    </tr>
                  ))}
                </tbody>
              </table>

              <div className="pagination">
                {pagination.lastPage > 0 && (
                  <>
                    <button 
                      onClick={() => handlePageChange(pagination.currentPage - 1)}
                      disabled={pagination.currentPage === 1}
                      className='pagination-btn'
                    >
                      Previous
                    </button>

                    {[...Array(pagination.lastPage)].map((_, index) => (
                      <button
                        key={index}
                        onClick={() => handlePageChange(index + 1)}
                        className={`pagination-btn ${pagination.currentPage === index + 1 ? 'active' : ''}`}

                      >
                        {index + 1}
                      </button>
                    ))}

                    <button 
                      onClick={() => handlePageChange(pagination.currentPage + 1)}
                      disabled={pagination.currentPage === pagination.lastPage}
                       className="pagination-btn"
                    >
                      Next
                    </button>
                  </>
                )}
              </div>
            </>
          )}
        </div>
      )}
    </div>
  );
};

export default Home;