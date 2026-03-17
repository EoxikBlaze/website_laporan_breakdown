"use client";

import * as React from "react";
import { cn } from "@/lib/utils";

interface TimeScrollProps {
  value: number;
  max: number;
  onChange: (val: number) => void;
  label: string;
}

const TimeScroll = ({ value, max, onChange, label }: TimeScrollProps) => {
  const scrollRef = React.useRef<HTMLDivElement>(null);
  const itemHeight = 40;

  const numbers = Array.from({ length: max }, (_, i) => i);

  const handleScroll = () => {
    if (!scrollRef.current) return;
    const scrollTop = scrollRef.current.scrollTop;
    const index = Math.round(scrollTop / itemHeight);
    if (index !== value && index < max) {
      onChange(index);
    }
  };

  React.useEffect(() => {
    if (scrollRef.current) {
      scrollRef.current.scrollTop = value * itemHeight;
    }
  }, []);

  return (
    <div className="flex flex-col items-center">
      <span className="text-[10px] uppercase font-bold text-muted-foreground mb-1">{label}</span>
      <div 
        ref={scrollRef}
        onScroll={handleScroll}
        className="h-[120px] w-12 overflow-y-auto scrollbar-hide snap-y snap-mandatory relative py-[40px]"
        style={{ scrollbarWidth: 'none', msOverflowStyle: 'none' }}
      >
        {numbers.map((n) => (
          <div 
            key={n} 
            className={cn(
              "h-[40px] flex items-center justify-center text-lg font-medium snap-center transition-all duration-200",
              value === n ? "text-primary scale-110" : "text-muted-foreground/40 scale-90"
            )}
          >
            {n.toString().padStart(2, '0')}
          </div>
        ))}
      </div>
    </div>
  );
};

export function IosClockPicker({ 
  initialTime = new Date(),
  onTimeChange 
}: { 
  initialTime?: Date,
  onTimeChange?: (date: Date) => void 
}) {
  const [hours, setHours] = React.useState(initialTime.getHours());
  const [minutes, setMinutes] = React.useState(initialTime.getMinutes());

  React.useEffect(() => {
    const newDate = new Date(initialTime);
    newDate.setHours(hours);
    newDate.setMinutes(minutes);
    onTimeChange?.(newDate);
  }, [hours, minutes]);

  return (
    <div className="bg-background border rounded-2xl p-4 shadow-xl w-fit flex gap-2 items-center relative overflow-hidden">
      {/* Background iOS Effect */}
      <div className="absolute inset-x-0 top-[60px] h-[40px] bg-primary/5 border-y border-primary/10 pointer-events-none" />
      
      <TimeScroll label="Hours" value={hours} max={24} onChange={setHours} />
      <div className="text-xl font-bold mt-4">:</div>
      <TimeScroll label="Minutes" value={minutes} max={60} onChange={setMinutes} />
    </div>
  );
}
