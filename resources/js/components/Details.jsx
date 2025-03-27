import { useParams } from 'react-router-dom';
import React, { useState, useEffect } from 'react';
import '../../css/details.css';

const Details = () => {
  const { id } = useParams();
  // console.log(id);
  
  const [ticket, setTicket] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [assigning, setAssigning] = useState(false);

  useEffect(() => {
   
    const fetchTicketDetails = async () => {
      try {
        const response = await fetch(`/api/tickets/${id}`);
        const data = await response.json();

        if (response.ok) {
          setTicket(data.ticket);  
        } else {
          setError(data.message); 
        }
      } catch (err) {
        setError('Error fetching ticket details');
      } finally {
        setLoading(false);  
      
      }
    };

    fetchTicketDetails();
  }, [id]);
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

  if (loading) {
    return <div>Loading...</div>;
  }

  if (error) {
    return <div>{error}</div>;
  }

  return (
    <div className="ticket-details">
    <h1>Ticket Details - {ticket?.title}</h1>
    <p><strong>Status:</strong> <span className={`status ${ticket?.status.toLowerCase()}`}>{ticket?.status}</span></p>
    <p><strong>Description:</strong> {ticket?.description}</p>
    <p><strong>Progress:</strong> {ticket?.progress}</p>
   
    <p><strong>Created At:</strong> {new Date(ticket?.created_at).toLocaleDateString()}</p>
   
    <p><strong>Owner ID:</strong> {ticket?.owner_id}</p>
    <p><strong>Agent ID:</strong> {ticket?.agent_id || 'No agent assigned'}</p>


    <button 
        className="assign-button" 
        onClick={handleAssignTicket} 
        disabled={!!ticket?.agent_id || assigning}
      >
        {assigning ? 'Assigning...' : 'Assign Ticket'}
      </button>


    
  </div>
  );
};

export default Details;
