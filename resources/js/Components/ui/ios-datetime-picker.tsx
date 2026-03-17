import * as React from "react";
import { format } from "date-fns";
import { id } from "date-fns/locale";
import { Calendar } from "@/Components/ui/calendar";
import { TimePicker } from "@/Components/ui/time-picker";
import { Popover, PopoverContent, PopoverTrigger } from "@/Components/ui/popover";
import { Button } from "@/Components/ui/button";
import { CalendarIcon, Clock } from "lucide-react";
import { cn } from "@/lib/utils";

interface IosDateTimePickerProps {
    name: string;
    initialValue?: string;
    label?: string;
}

export function IosDateTimePicker({ name, initialValue, label }: IosDateTimePickerProps) {
    const [date, setDate] = React.useState<Date | undefined>(
        initialValue ? new Date(initialValue) : new Date()
    );
    const [isOpen, setIsOpen] = React.useState(false);

    const formattedValue = date ? format(date, "yyyy-MM-dd HH:mm") : "";
    const displayValue = date ? format(date, "dd/MM/yyyy, HH:mm") : "Pilih waktu...";

    const handleTimeChange = (newDate: Date) => {
        setDate(newDate);
    };

    return (
        <div className="w-full">
            <input type="hidden" name={name} value={formattedValue} />
            
            <Popover open={isOpen} onOpenChange={setIsOpen}>
                <PopoverTrigger asChild>
                    <Button
                        variant={"outline"}
                        className={cn(
                            "w-full justify-start text-left font-medium h-12 px-4 border-neutral-200 rounded-xl bg-white hover:bg-neutral-50 hover:border-blue-300 transition-all duration-200 shadow-sm",
                            !date && "text-neutral-400"
                        )}
                    >
                        <CalendarIcon className="mr-3 h-4 w-4 text-blue-500" />
                        <span className="flex-1 text-sm">{displayValue}</span>
                    </Button>
                </PopoverTrigger>
                <PopoverContent 
                    className="w-[92vw] sm:w-auto p-0 border border-neutral-200 shadow-2xl rounded-[32px] overflow-hidden bg-white/95 backdrop-blur-md mt-2 max-h-[90vh] flex flex-col sm:max-h-none sm:flex-row sm:overflow-visible sm:bg-white" 
                    align="center"
                    sideOffset={12}
                >
                    <div className="flex flex-col h-full max-h-[85vh] sm:max-h-none">
                        {/* Header: Fixed at top */}
                        <div className="px-6 py-6 bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800 text-white shadow-xl flex-shrink-0">
                            <div className="flex items-center justify-between">
                                <div className="flex flex-col">
                                    <p className="text-[10px] uppercase tracking-[0.25em] font-black opacity-60 mb-1">Pilih Waktu</p>
                                    <span className="text-base font-bold">
                                        {date ? format(date, "dd MMMM yyyy", { locale: id }) : "Pilih Tanggal"}
                                    </span>
                                </div>
                                <div className="px-5 py-2.5 bg-white/10 rounded-2xl text-xl font-black backdrop-blur-2xl border border-white/20 shadow-inner">
                                    {date ? format(date, "HH:mm") : "--:--"}
                                </div>
                            </div>
                        </div>

                        {/* Scrollable Body on Mobile */}
                        <div className="flex-1 overflow-y-auto sm:overflow-visible">
                            <div className="flex flex-col sm:flex-row divide-y sm:divide-y-0 sm:divide-x divide-neutral-100">
                                {/* Left: Calendar */}
                                <div className="p-3 sm:p-6 bg-white sm:bg-transparent flex justify-center">
                                    <Calendar
                                        mode="single"
                                        selected={date}
                                        onSelect={(d) => d && setDate(prev => {
                                            if (!prev) return d;
                                            const newDate = new Date(d);
                                            newDate.setHours(prev.getHours());
                                            newDate.setMinutes(prev.getMinutes());
                                            return newDate;
                                        })}
                                        locale={id}
                                        className="p-1 sm:p-0"
                                    />
                                </div>

                                {/* Right: Time & Action */}
                                <div className="px-8 py-8 sm:w-[280px] bg-neutral-50/20 sm:bg-neutral-50/40 flex flex-col justify-between">
                                    <div className="space-y-8">
                                        <div className="flex items-center gap-3">
                                            <div className="p-2.5 bg-blue-100 rounded-2xl text-blue-600 shadow-sm border border-blue-200/50">
                                                <Clock size={18} />
                                            </div>
                                            <span className="text-[12px] font-black text-neutral-400 uppercase tracking-widest">Waktu Presisi</span>
                                        </div>
                                        
                                        <div className="flex justify-center sm:justify-start scale-125 sm:scale-100 origin-center sm:origin-left py-6 sm:py-2">
                                            <TimePicker 
                                                value={date} 
                                                onChange={handleTimeChange} 
                                            />
                                        </div>
                                    </div>
                                    
                                    <Button 
                                        className="w-full mt-12 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white rounded-2xl h-14 shadow-2xl shadow-blue-600/30 font-black transition-all active:scale-[0.95] text-sm tracking-widest gap-3 group"
                                        onClick={() => setIsOpen(false)}
                                    >
                                        <span>KONFIRMASI</span>
                                        <svg className="w-5 h-5 group-hover:translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="3" strokeLinecap="round" strokeLinejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </div>
                </PopoverContent>
            </Popover>
        </div>
    );
}
