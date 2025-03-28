import { useParams } from 'react-router-dom';
import React, { useState, useEffect } from 'react';
import Chat from './Chat.jsx';
import '../../css/details.css';

const Details = () => {
  const { id } = useParams();
 
  
  const [ticket, setTicket] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [agentName, setAgent] = useState('No agent assigned');
  const [assigning, setAssigning] = useState(false);
  
  

  useEffect(() => {
   
    const fetchTicketDetails = async () => {
      try {
        const response = await fetch(`/api/tickets/${id}`);
        const data = await response.json();

        if (response.ok) {
          console.log(data.ticket);
          
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
    // setAssigning(true); 
    try {
      
      const response = await fetch(`/api/tickets/${id}/assign`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${localStorage.getItem('authToken')}`,
        },
        body: JSON.stringify({ agent_id:  localStorage.getItem('userId')}), 
      });
      const data = await response.json();

      if (response.ok) {
        

        setTicket((prevTicket) => ({ ...prevTicket, agent_id: data.name[1] ,agent_name: data.name[0] }));

       
        console.log(data.name[0]);
        setAgent(data.name[0])
        
        
      } else {
        setError(data.message);
      }
    } catch (err) {
      setError('Error assigning the ticket');
    } finally {
      setAssigning(false);
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
   
    <p><strong>Owner ID:</strong> {ticket?.owner_name}</p>
    <p><strong>Agent ID:</strong> {ticket?.agent_id ? ticket?.agent_name : 'No agent assigned'}</p>


    <button 
        className="assign-button" 
        onClick={handleAssignTicket} 
        disabled={!!ticket?.agent_id || assigning || localStorage.getItem('userRole')=='client'}
      >
        {assigning ? 'Assigning...' : 'Assign Ticket'}
      </button>

    <div>
       <Chat ticketId={id}/>
   </div>

    

  </div>



  );
};

export default Details;
