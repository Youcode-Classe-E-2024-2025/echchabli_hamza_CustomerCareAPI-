import React, { useEffect, useState } from 'react';

const AdminDash = () => {
  const [ticketActivities, setTicketActivities] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchTicketActivities = async () => {
      try {
        const response = await fetch('/api/activity', {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('authToken')}`,
          },
        });

        if (!response.ok) {
          throw new Error('Failed to fetch ticket activities');
        }

        const data = await response.json();
        setTicketActivities(data.res);
        setLoading(false);
      } catch (err) {
        setError(err.message);
        setLoading(false);
      }
    };

    fetchTicketActivities();
  }, []);

  if (loading) return <p>Loading...</p>;
  if (error) return <p>Error: {error}</p>;

  return (
    <div className="tickets-container">
      <h1>All Tickets and Activities</h1>
      {ticketActivities.length === 0 ? (
        <p>No activities available.</p>
      ) : (
        <table className="tickets-table">
          <thead>
            <tr>
              <th>Ticket Title</th>
              <th>Action</th>
              <th>User</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            {ticketActivities.map((activity) => (
              <tr key={activity.id}>
                 <td dangerouslySetInnerHTML={{ __html: activity.title|| 'N/A' }}></td>
                <td>{activity.action}</td>
                <td>{activity.user_name}</td>
                <td>{new Date(activity.created_at).toLocaleString()}</td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </div>
  );
};

export default AdminDash;
