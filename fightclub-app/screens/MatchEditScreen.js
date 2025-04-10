import React, { useState } from 'react';
import { View, TextInput, Button, Alert } from 'react-native';
import { updateMatch } from '../services/api';

export default function MatchEditScreen({ route, navigation }) {
  const matchId = route.params?.match?.match_id;
  const match = route.params?.match;
  const user = route.params?.user;
  const [newDate, setNewDate] = useState('');

  const isValidDate = (dateStr) => {
    // Matches YYYY-MM-DD format and checks for a real date
    const regex = /^\d{4}-\d{2}-\d{2}$/;
    return regex.test(dateStr) && !isNaN(new Date(dateStr).getTime());
  };

  const update = async () => {
    if (!newDate.trim()) {
      Alert.alert("Missing Date", "Please enter a date.");
      return;
    }
  
    const isValidDate = /^\d{4}-\d{2}-\d{2}$/.test(newDate) && !isNaN(new Date(newDate).getTime());
    if (!isValidDate) {
      Alert.alert("Invalid Date", "Please enter a valid date in YYYY-MM-DD format.");
      return;
    }
  
    try {
      console.log("Attempting to update match:", { matchId, fight_date: newDate });
  
      const res = await updateMatch(matchId, { fight_date: newDate });
  
      console.log("✅ Update response:", res.data);
      Alert.alert("Updated!");
      navigation.navigate("Dashboard", { user });
 // optional: pass user back
    } catch (err) {
      console.log("❌ Update error:", err.response?.data || err.message || err);
      Alert.alert("Error", err.response?.data?.error || "Update failed");
    }
  };
  

  return (
    <View style={{ padding: 20 }}>
      <TextInput
        placeholder="New Date (YYYY-MM-DD)"
        value={newDate}
        onChangeText={setNewDate}
        style={{ borderWidth: 1, marginBottom: 10, padding: 8 }}
      />
      <Button title="Update Match" onPress={update} />
    </View>
  );
}
